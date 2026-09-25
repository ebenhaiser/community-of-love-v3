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
            // Activity 1 (COOL 1: 2026-08-07)
            ['activity_id' => 1, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:05:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:10:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:02:00', 'note' => null],
            ['activity_id' => 1, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-07 19:15:00', 'note' => null],

            // Activity 2 (COOL 1: 2026-08-14)
            ['activity_id' => 2, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:00:00', 'note' => null],
            ['activity_id' => 2, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:20:00', 'note' => null],
            ['activity_id' => 2, 'member_id' => 3, 'attendance_status_id' => 4, 'attendance_time' => null, 'note' => 'Sakit demam'],
            ['activity_id' => 2, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-14 19:10:00', 'note' => null],

            // Activity 3 (COOL 1: 2026-08-21)
            ['activity_id' => 3, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:00:00', 'note' => null],
            ['activity_id' => 3, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 3, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:03:00', 'note' => null],
            ['activity_id' => 3, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-21 19:05:00', 'note' => null],

            // Activity 4 (COOL 1: 2026-08-28)
            ['activity_id' => 4, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:30:00', 'note' => null],
            ['activity_id' => 4, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 4, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:35:00', 'note' => null],
            ['activity_id' => 4, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-28 19:28:00', 'note' => null],

            // Activity 5 (COOL 1: 2026-09-04)
            ['activity_id' => 5, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:00:00', 'note' => null],
            ['activity_id' => 5, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan'],
            ['activity_id' => 5, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:04:00', 'note' => null],
            ['activity_id' => 5, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-04 19:08:00', 'note' => null],

            // Activity 6 (COOL 1: 2026-09-11)
            ['activity_id' => 6, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-11 19:05:00', 'note' => null],
            ['activity_id' => 6, 'member_id' => 2, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Tanpa keterangan (perlu follow up)'],
            ['activity_id' => 6, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-11 19:02:00', 'note' => null],
            ['activity_id' => 6, 'member_id' => 4, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Lembur kantor'],

            // Activity 7 (COOL 1: 2026-09-18)
            ['activity_id' => 7, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-18 19:00:00', 'note' => null],
            ['activity_id' => 7, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-18 19:15:00', 'note' => 'Kembali hadir'],
            ['activity_id' => 7, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-18 19:02:00', 'note' => null],
            ['activity_id' => 7, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-18 19:07:00', 'note' => null],

            // Activity 9 (COOL 2: 2026-08-08)
            ['activity_id' => 9, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-08 18:35:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-08 18:30:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-08 18:40:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 12, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-08 18:32:00', 'note' => null],
            ['activity_id' => 9, 'member_id' => 14, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Ada acara keluarga'],

            // Activity 10 (COOL 2: 2026-08-22)
            ['activity_id' => 10, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-22 17:05:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-22 17:10:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-22 17:00:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 12, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-22 17:15:00', 'note' => null],
            ['activity_id' => 10, 'member_id' => 14, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-22 17:20:00', 'note' => null],

            // Activity 11 (COOL 2: 2026-09-05)
            ['activity_id' => 11, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-05 19:00:00', 'note' => null],
            ['activity_id' => 11, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-05 19:05:00', 'note' => null],
            ['activity_id' => 11, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-05 19:10:00', 'note' => null],
            ['activity_id' => 11, 'member_id' => 12, 'attendance_status_id' => 4, 'attendance_time' => null, 'note' => 'Flu dan istirahat'],
            ['activity_id' => 11, 'member_id' => 14, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-05 19:08:00', 'note' => null],

            // Activity 12 (COOL 2: 2026-09-19)
            ['activity_id' => 12, 'member_id' => 5, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-19 19:00:00', 'note' => null],
            ['activity_id' => 12, 'member_id' => 8, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-19 19:02:00', 'note' => null],
            ['activity_id' => 12, 'member_id' => 10, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-19 19:04:00', 'note' => null],
            ['activity_id' => 12, 'member_id' => 12, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-19 19:10:00', 'note' => null],
            ['activity_id' => 12, 'member_id' => 14, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-19 19:05:00', 'note' => null],

            // Activity 13 (COOL 3: 2026-08-12)
            ['activity_id' => 13, 'member_id' => 15, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-12 10:05:00', 'note' => null],
            ['activity_id' => 13, 'member_id' => 16, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-12 10:00:00', 'note' => null],
            ['activity_id' => 13, 'member_id' => 21, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-12 10:10:00', 'note' => null],
            ['activity_id' => 13, 'member_id' => 22, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-12 10:02:00', 'note' => null],

            // Activity 14 (COOL 3: 2026-09-09)
            ['activity_id' => 14, 'member_id' => 15, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-09 09:35:00', 'note' => null],
            ['activity_id' => 14, 'member_id' => 16, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-09 09:30:00', 'note' => null],
            ['activity_id' => 14, 'member_id' => 21, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Check-up dokter'],
            ['activity_id' => 14, 'member_id' => 22, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-09 09:40:00', 'note' => null],

            // Activity 15 (COOL 4: 2026-08-15)
            ['activity_id' => 15, 'member_id' => 6, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:05:00', 'note' => null],
            ['activity_id' => 15, 'member_id' => 7, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:00:00', 'note' => null],
            ['activity_id' => 15, 'member_id' => 11, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:10:00', 'note' => null],
            ['activity_id' => 15, 'member_id' => 17, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:02:00', 'note' => null],
            ['activity_id' => 15, 'member_id' => 18, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:15:00', 'note' => null],
            ['activity_id' => 15, 'member_id' => 23, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-15 16:20:00', 'note' => null],

            // Activity 16 (COOL 4: 2026-09-12)
            ['activity_id' => 16, 'member_id' => 6, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 16:35:00', 'note' => null],
            ['activity_id' => 16, 'member_id' => 7, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 16:30:00', 'note' => null],
            ['activity_id' => 16, 'member_id' => 11, 'attendance_status_id' => 2, 'attendance_time' => null, 'note' => 'Ujian praktikum'],
            ['activity_id' => 16, 'member_id' => 17, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 16:32:00', 'note' => null],
            ['activity_id' => 16, 'member_id' => 18, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 16:40:00', 'note' => null],
            ['activity_id' => 16, 'member_id' => 23, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-12 16:38:00', 'note' => null],

            // Activity 17 (COOL 5: 2026-08-20)
            ['activity_id' => 17, 'member_id' => 9, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-20 19:35:00', 'note' => null],
            ['activity_id' => 17, 'member_id' => 13, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-20 19:30:00', 'note' => null],
            ['activity_id' => 17, 'member_id' => 19, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-20 19:40:00', 'note' => null],

            // Activity 18 (COOL 5: 2026-09-17)
            ['activity_id' => 18, 'member_id' => 9, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-17 19:32:00', 'note' => null],
            ['activity_id' => 18, 'member_id' => 13, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Business trip Surabaya'],
            ['activity_id' => 18, 'member_id' => 19, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-17 19:35:00', 'note' => null],

            // Activity 19 (COOL 6: 2026-08-19)
            ['activity_id' => 19, 'member_id' => 20, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-19 14:05:00', 'note' => null],
            ['activity_id' => 19, 'member_id' => 24, 'attendance_status_id' => 1, 'attendance_time' => '2026-08-19 14:00:00', 'note' => null],

            // Activity 20 (COOL 6: 2026-09-16)
            ['activity_id' => 20, 'member_id' => 20, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-16 14:02:00', 'note' => null],
            ['activity_id' => 20, 'member_id' => 24, 'attendance_status_id' => 1, 'attendance_time' => '2026-09-16 14:06:00', 'note' => null],
        ];

        foreach ($records as $index => $rec) {
            Attendance::updateOrCreate(
                ['attendance_id' => $index + 1],
                array_merge($rec, [
                    'is_deleted' => false,
                    'created_by' => 1,
                    'date_created' => $now,
                ])
            );
        }
    }
}
