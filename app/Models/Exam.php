<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasLocalizedFields;

    protected $fillable = ['name', 'name_bn', 'exam_type', 'exam_type_bn', 'academic_session_id', 'start_date', 'end_date', 'is_published'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function getNameAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'name');
    }

    public function getExamTypeAttribute(?string $value): ?string
    {
        return $this->localizedValue($value, 'exam_type');
    }

    public function academicSession(): BelongsTo
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function examSubjects(): HasMany
    {
        return $this->hasMany(ExamSubject::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
