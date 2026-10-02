<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const CACHE_KEY = 'myschool.settings';

    /**
     * All settings keyed by their name.
     *
     * @return array<string, string|null>
     */
    public static function all_settings(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Read a setting, using its Bengali override when the site is in Bangla
     * and a translation has been provided.
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $all = static::all_settings();

        if (app()->getLocale() === 'bn') {
            $bengali = $all[$key . '_bn'] ?? null;

            if (is_string($bengali) && trim($bengali) !== '') {
                return $bengali;
            }
        }

        return $all[$key] ?? $default;
    }

    /**
     * All settings with Bengali overrides applied for the active locale.
     * This is what gets shared with every view.
     *
     * @return array<string, string|null>
     */
    public static function localizedAll(): array
    {
        $all = static::all_settings();

        if (app()->getLocale() !== 'bn') {
            return $all;
        }

        $localized = $all;

        foreach ($all as $key => $value) {
            if (str_ends_with($key, '_bn')) {
                $base = substr($key, 0, -3);

                if (is_string($value) && trim($value) !== '') {
                    $localized[$base] = $value;
                }
            }
        }

        return $localized;
    }

    /**
     * Read the raw stored value for a key, ignoring locale overrides.
     */
    public static function getRaw(string $key, ?string $default = null): ?string
    {
        return static::all_settings()[$key] ?? $default;
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
