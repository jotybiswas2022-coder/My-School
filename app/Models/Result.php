<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks',
        'full_marks',
        'grade',
        'gpa',
        'is_published',
    ];

    protected $casts = [
        'marks' => 'decimal:2',
        'gpa' => 'decimal:2',
        'is_published' => 'boolean',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Convert a percentage into a grade + gpa using a standard 5.0 scale.
     *
     * @return array{grade:string, gpa:float}
     */
    public static function gradeFor(float $marks, int $fullMarks = 100): array
    {
        $percent = $fullMarks > 0 ? ($marks / $fullMarks) * 100 : 0;

        return match (true) {
            $percent >= 80 => ['grade' => 'A+', 'gpa' => 5.00],
            $percent >= 70 => ['grade' => 'A', 'gpa' => 4.00],
            $percent >= 60 => ['grade' => 'A-', 'gpa' => 3.50],
            $percent >= 50 => ['grade' => 'B', 'gpa' => 3.00],
            $percent >= 40 => ['grade' => 'C', 'gpa' => 2.00],
            $percent >= 33 => ['grade' => 'D', 'gpa' => 1.00],
            default => ['grade' => 'F', 'gpa' => 0.00],
        };
    }

    public static function gradeColor(string $grade): string
    {
        return match ($grade) {
            'A+', 'A' => '#16A34A',
            'A-' => '#2563EB',
            'B' => '#0EA5E9',
            'C' => '#F59E0B',
            'D' => '#EA580C',
            default => '#DC2626',
        };
    }

    public function percentage(): float
    {
        return $this->full_marks > 0 ? round(($this->marks / $this->full_marks) * 100, 2) : 0;
    }
}
