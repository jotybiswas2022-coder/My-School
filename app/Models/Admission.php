<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'application_id',
        'student_name',
        'date_of_birth',
        'gender',
        'applying_class',
        'previous_school',
        'guardian_name',
        'guardian_phone',
        'email',
        'address',
        'additional_info',
        'status',
    ];

    protected $casts = ['date_of_birth' => 'date'];

    public static function statuses(): array
    {
        return ['pending', 'approved', 'rejected'];
    }

    public static function generateApplicationId(): string
    {
        do {
            $id = 'ADM-' . date('Y') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        } while (static::where('application_id', $id)->exists());

        return $id;
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'approved' => '#16A34A',
            'rejected' => '#DC2626',
            default => '#F59E0B',
        };
    }
}
