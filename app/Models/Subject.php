<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasLocalizedFields;

    protected $fillable = ['name', 'name_bn', 'code', 'description', 'description_bn', 'class_id', 'teacher_id'];

    public function getNameAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'name');
    }

    public function getDescriptionAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'description');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subjects', 'subject_id', 'class_id')
            ->withTimestamps();
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
