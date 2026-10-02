<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSession extends Model
{
    protected $fillable = ['name', 'start_date', 'end_date', 'is_current'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    public static function current(): ?self
    {
        return static::where('is_current', true)->first() ?? static::latest('id')->first();
    }
}
