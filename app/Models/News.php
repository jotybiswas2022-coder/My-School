<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasLocalizedFields;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'title_bn',
        'category',
        'description',
        'description_bn',
        'featured_image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getTitleAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'title');
    }

    public function getDescriptionAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'description');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at');
    }

    public static function categories(): array
    {
        return ['General', 'Academic', 'Sports', 'Cultural', 'Achievement', 'Campus'];
    }
}
