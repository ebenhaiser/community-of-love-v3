<?php

namespace Database\Seeders;

use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Status IDs: 1: PRESENT, 2: ABSENT, 3: EXCUSED, 4: SICK
        $records = [
            // Activity 1 (2026-08-07)
            ['activity_id' => 1, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:05:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:10:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:02:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:15:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 5, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Sedang dinas luar kota'],

            // Activity 2 (2026-08-14)
            ['activity_id' => 2, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:00:00', 'note' => null],
            ['activity_id' => 2, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:20:00', 'note' => null],
            ['activity_id' => 2, 'member_id' => 3, 'attendance_status_id' => 4, 'attendance_time' => null, 'note' => 'Sakit demam'],
            ['activity_id' => 2, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:10:00', 'note' => null],
            ['activity_id' => 2, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:05:00', 'note' => null],

            // Activity 3 (2026-08-21)
            ['activity_id' => 3, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:00:00', 'note' => null],
            ['activity_id' => 3, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 3, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:03:00', 'note' => null],
            ['activity_id' => 3, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:05:00', 'note' => null],
            ['activity_id' => 3, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:12:00', 'note' => null],

            // Activity 4 (2026-08-28)
            ['activity_id' => 4, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:30:00', 'note' => null],
            ['activity_id' => 4, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 4, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:35:00', 'note' => null],
            ['activity_id' => 4, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:28:00', 'note' => null],
            ['activity_id' => 4, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:32:00', 'note' => null],

            // Activity 5 (2026-09-04)
            ['activity_id' => 5, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:00:00', 'note' => null],
            ['activity_id' => 5, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 5, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:04:00', 'note' => null],
            ['activity_id' => 5, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:08:00', 'note' => null],
            ['activity_id' => 5, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:01:00', 'note' => null],

            // Activity 6 (2026-09-11)
            ['activity_id' => 6, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-11 19:05:00', 'note' => null],
            ['activity_id' => 6, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan (perlu follow up)'],
            ['activity_id' => 6, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-11 19:02:00', 'note' => null],
            ['activity_id' => 6, 'member_id' => 4, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Lembur kantor'],
            ['activity_id' => 6, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-11 19:15:00', 'note' => null],

            // Activity 8 (COOL 2: 2026-08-15)
            ['activity_id' => 8, 'member_id' => 6, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 18:05:00', 'note' => null],
            ['activity_id' => 8, 'member_id' => 7, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 18:02:00', 'note' => null],
            ['activity_id' => 8, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 18:10:00', 'note' => null],
            ['activity_id' => 8, 'member_id' => 9, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 18:00:00', 'note' => null],
            ['activity_id' => 8, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 18:07:00', 'note' => null],

            // Activity 9 (COOL 2: 2026-08-29)
            ['activity_id' => 9, 'member_id' => 6, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-29 18:05:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 7, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-29 18:00:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 8, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Acara keluarga'],
            ['activity_id' => 9, 'member_id' => 9, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-29 18:15:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-29 18:03:00', 'note' => null],

            // Activity 10 (COOL 2: 2026-09-12)
            ['activity_id' => 10, 'member_id' => 6, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 18:00:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 7, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 18:02:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 18:08:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 9, 'attendance_status_id' => 4, 'attendance_time' => null, 'note' => 'Sakit flu'],
            ['activity_id' => 10, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 18:04:00', 'note' => null],
        ];

        foreach ($records as $record) {
            Attendance::updateOrCreate(
                [
                    'activity_id' => $record['activity_id'],
                    'member_id' => $record['member_id'],
                ],
                array_merge($record, [
                    'is_deleted' => false,
                    'created_by' => 2,
                    'date_created' => $now,
                ])
            );
        }
    }
}
