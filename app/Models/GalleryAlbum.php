<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryAlbum extends Model
{
    use HasLocalizedFields;

    protected $fillable = ['title', 'title_bn', 'category', 'description', 'description_bn', 'cover_image'];

    public function getTitleAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'title');
    }

    public function getDescriptionAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'description');
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class, 'album_id');
    }

    public function getCoverUrlAttribute(): string
    {
        $first = $this->images->first();

        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : ($first ? asset('storage/' . $first->image) : asset('images/placeholder.svg'));
    }
}
