<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceStatus extends Model
{
    use HasFactory;

    protected $table = 'attendance_statuses';

    protected $primaryKey = 'attendance_status_id';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'attendance_status_id', 'attendance_status_id');
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'attendance_status_id', 'attendance_status_id');
    }
}
