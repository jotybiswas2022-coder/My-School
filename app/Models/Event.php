<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasLocalizedFields;

    protected $fillable = [
        'title',
        'title_bn',
        'description',
        'description_bn',
        'image',
        'event_date',
        'event_time',
        'location',
        'location_bn',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function getTitleAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'title');
    }

    public function getDescriptionAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'description');
    }

    public function getLocationAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'location');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->whereDate('event_date', '>=', now()->toDateString())->orderBy('event_date');
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->whereDate('event_date', '<', now()->toDateString())->orderByDesc('event_date');
    }
}
