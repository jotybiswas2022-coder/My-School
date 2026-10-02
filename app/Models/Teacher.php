<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasLocalizedFields;

    protected $fillable = [
        'name',
        'name_bn',
        'email',
        'phone',
        'designation',
        'designation_bn',
        'department',
        'department_bn',
        'qualification',
        'qualification_bn',
        'experience',
        'bio',
        'bio_bn',
        'photo',
        'join_date',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'join_date' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getNameAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'name');
    }

    public function getDesignationAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'designation');
    }

    public function getDepartmentAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'department');
    }

    public function getQualificationAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'qualification');
    }

    public function getBioAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'bio');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Locale-safe initials (works for Bengali names too).
     */
    public function initials(): string
    {
        $parts = preg_split('/\s+/u', trim((string) $this->name));

        $initials = mb_substr($parts[0] ?? '', 0, 1, 'UTF-8')
            . mb_substr($parts[1] ?? '', 0, 1, 'UTF-8');

        return mb_strtoupper($initials, 'UTF-8');
    }
}
