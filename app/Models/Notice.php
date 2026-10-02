<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notice extends Model
{
    use HasLocalizedFields;

    protected $fillable = [
        'title',
        'title_bn',
        'category',
        'description',
        'description_bn',
        'attachment',
        'is_published',
        'published_at',
        'user_id',
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
        return ['General', 'Academic', 'Exam', 'Admission', 'Event', 'Holiday', 'Urgent'];
    }
}
