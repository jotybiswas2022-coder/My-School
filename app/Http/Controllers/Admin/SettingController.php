<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use UploadsFiles;

    /**
     * Keys that are managed through the settings form.
     *
     * @var array<int, string>
     */
    /**
     * Bengali overrides for the text settings above.
     *
     * @var array<int, string>
     */
    public const BENGALI_KEYS = [
        'school_name_bn',
        'tagline_bn',
        'address_bn',
        'office_hours_bn',
        'about_description_bn',
        'mission_bn',
        'vision_bn',
        'principal_name_bn',
        'principal_designation_bn',
        'principal_message_bn',
        'footer_text_bn',
    ];

    public const IMAGE_KEYS = [
        'logo',
        'favicon',
        'principal_photo',
        'hero_image',
    ];

    public const TEXT_KEYS = [
        'school_name',
        'tagline',
        'email',
        'phone',
        'address',
        'office_hours',
        'principal_name',
        'principal_designation',
        'principal_message',
        'established_year',
        'about_description',
        'mission',
        'vision',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'linkedin',
        'footer_text',
    ];

    public function edit()
    {
        return view('backend.settings.edit', [
            'values' => collect(array_merge(self::TEXT_KEYS, self::BENGALI_KEYS))
                ->mapWithKeys(fn ($key) => [$key => Setting::getRaw($key)])
                ->all(),
            'admissionOpen' => Setting::get('admission_open', '1') === '1',
            'logo' => Setting::get('logo'),
            'favicon' => Setting::get('favicon'),
            'principalPhoto' => Setting::get('principal_photo'),
            'heroImage' => Setting::get('hero_image'),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'school_name' => ['required', 'string', 'max:120'],
            'tagline' => ['nullable', 'string', 'max:180'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:25'],
            'address' => ['nullable', 'string', 'max:255'],
            'office_hours' => ['nullable', 'string', 'max:120'],
            'principal_name' => ['nullable', 'string', 'max:120'],
            'principal_designation' => ['nullable', 'string', 'max:120'],
            'principal_message' => ['nullable', 'string', 'max:5000'],
            'established_year' => ['nullable', 'integer', 'min:1800', 'max:' . now()->year],
            'about_description' => ['nullable', 'string', 'max:3000'],
            'mission' => ['nullable', 'string', 'max:2000'],
            'vision' => ['nullable', 'string', 'max:2000'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'logo' => $this->imageRules(),
            'favicon' => $this->imageRules(),
            'principal_photo' => $this->imageRules(),
            'hero_image' => $this->imageRules(),
            'school_name_bn' => ['nullable', 'string', 'max:120'],
            'tagline_bn' => ['nullable', 'string', 'max:180'],
            'address_bn' => ['nullable', 'string', 'max:255'],
            'office_hours_bn' => ['nullable', 'string', 'max:120'],
            'about_description_bn' => ['nullable', 'string', 'max:3000'],
            'mission_bn' => ['nullable', 'string', 'max:2000'],
            'vision_bn' => ['nullable', 'string', 'max:2000'],
            'principal_name_bn' => ['nullable', 'string', 'max:120'],
            'principal_designation_bn' => ['nullable', 'string', 'max:120'],
            'principal_message_bn' => ['nullable', 'string', 'max:5000'],
            'footer_text_bn' => ['nullable', 'string', 'max:255'],
        ]);

        foreach (array_merge(self::TEXT_KEYS, self::BENGALI_KEYS) as $key) {
            Setting::put($key, $data[$key] ?? null);
        }

        Setting::put('admission_open', $request->boolean('admission_open') ? '1' : '0');

        foreach (self::IMAGE_KEYS as $imageKey) {
            if ($path = $this->uploadImage($request->file($imageKey), 'settings')) {
                $this->deleteImage(Setting::get($imageKey));
                Setting::put($imageKey, $path);
            }
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    public function destroyImage(string $key)
    {
        abort_unless(in_array($key, self::IMAGE_KEYS, true), 404);

        $this->deleteImage(Setting::get($key));
        Setting::put($key, null);

        return back()->with('success', ucfirst(str_replace('_', ' ', $key)) . ' removed successfully.');
    }
}
