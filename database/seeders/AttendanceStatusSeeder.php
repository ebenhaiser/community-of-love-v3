<?php

namespace Database\Seeders;

use App\Models\AttendanceStatus;
use Illuminate\Database\Seeder;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'attendance_status_id' => 1,
                'code' => 'PRESENT',
                'name' => 'Hadir',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'attendance_status_id' => 2,
                'code' => 'ABSENT',
                'name' => 'Tidak Hadir',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'attendance_status_id' => 3,
                'code' => 'EXCUSED',
                'name' => 'Izin',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'attendance_status_id' => 4,
                'code' => 'SICK',
                'name' => 'Sakit',
                'is_active' => true,
                'is_deleted' => false,
            ],
        ];

        foreach ($statuses as $status) {
            AttendanceStatus::updateOrCreate(['attendance_status_id' => $status['attendance_status_id']], $status);
        }
    }
}
