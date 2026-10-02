<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'class_id',
        'section_id',
        'roll_number',
        'guardian_name',
        'guardian_phone',
        'address',
        'photo',
        'admission_date',
        'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Attendance summary as percentages.
     *
     * @return array{present:int, absent:int, late:int, total:int, percentage:float}
     */
    public function attendanceSummary(): array
    {
        $rows = $this->attendances()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');

        $present = (int) ($rows['present'] ?? 0);
        $absent = (int) ($rows['absent'] ?? 0);
        $late = (int) ($rows['late'] ?? 0);
        $total = $present + $absent + $late;

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'percentage' => $total > 0 ? round((($present + $late) / $total) * 100, 1) : 0,
        ];
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
