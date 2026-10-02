<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsFiles
{
    /**
     * Validate, rename and store an uploaded image on the public disk.
     *
     * @param  UploadedFile|null  $file
     * @return string|null  The stored relative path.
     */
    protected function uploadImage(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }

        $name = $file->hashName();

        return $file->storeAs($folder, $name, 'public');
    }

    protected function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Validation rules shared by image upload fields.
     *
     * @return array<int, string>
     */
    protected function imageRules(bool $required = false): array
    {
        return array_filter([
            $required ? 'required' : 'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp,gif,svg',
            'max:4096',
        ]);
    }
}
