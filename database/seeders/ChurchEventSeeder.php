<?php

namespace Database\Seeders;

use App\Models\ChurchEvent;
use App\Models\EventAttendance;
use App\Models\EventCool;
use App\Models\EventMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ChurchEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $events = [
            [
                'event_id' => 1,
                'event_code' => 'EVT-SLM-2026-001',
                'name' => 'Ibadah COOL Gabungan Q3 2026',
                'event_date' => '2026-09-20',
                'start_time' => '17:00:00',
                'end_time' => '19:30:00',
                'location' => 'Main Sanctuary GBI Salemba Lt. 3',
                'description' => 'Ibadah kebersamaan seluruh kelompok COOL cabang Salemba kuartal 3.',
                'status' => 'SCHEDULED',
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'event_id' => 2,
                'event_code' => 'EVT-SLM-2026-000',
                'name' => 'Ibadah COOL Gabungan Pertengahan Tahun 2026',
                'event_date' => '2026-06-28',
                'start_time' => '17:00:00',
                'end_time' => '19:30:00',
                'location' => 'Main Sanctuary GBI Salemba Lt. 3',
                'description' => 'Ibadah kebersamaan COOL semester pertama tahun 2026.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
        ];

        foreach ($events as $event) {
            ChurchEvent::updateOrCreate(['event_id' => $event['event_id']], $event);
        }

        // Participating COOLs
        $eventCools = [
            ['event_cool_id' => 1, 'event_id' => 1, 'cool_id' => 1, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_cool_id' => 2, 'event_id' => 1, 'cool_id' => 2, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_cool_id' => 3, 'event_id' => 2, 'cool_id' => 1, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_cool_id' => 4, 'event_id' => 2, 'cool_id' => 2, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
        ];

        foreach ($eventCools as $ec) {
            EventCool::updateOrCreate(['event_cool_id' => $ec['event_cool_id']], $ec);
        }

        // Participating Members for Event 2
        $eventMembers = [
            ['event_member_id' => 1, 'event_id' => 2, 'member_id' => 1, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_member_id' => 2, 'event_id' => 2, 'member_id' => 2, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_member_id' => 3, 'event_id' => 2, 'member_id' => 3, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_member_id' => 4, 'event_id' => 2, 'member_id' => 4, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_member_id' => 5, 'event_id' => 2, 'member_id' => 5, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
        ];

        foreach ($eventMembers as $em) {
            EventMember::updateOrCreate(['event_member_id' => $em['event_member_id']], $em);
        }

        // Event Attendance for Event 2
        $eventAttendances = [
            ['event_attendance_id' => 1, 'event_id' => 2, 'member_id' => 1, 'attendance_status_id' => 1, 'attendance_time' => '2026-06-28 17:05:00', 'note' => null, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_attendance_id' => 2, 'event_id' => 2, 'member_id' => 2, 'attendance_status_id' => 1, 'attendance_time' => '2026-06-28 17:10:00', 'note' => null, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_attendance_id' => 3, 'event_id' => 2, 'member_id' => 3, 'attendance_status_id' => 1, 'attendance_time' => '2026-06-28 17:02:00', 'note' => null, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_attendance_id' => 4, 'event_id' => 2, 'member_id' => 4, 'attendance_status_id' => 1, 'attendance_time' => '2026-06-28 17:15:00', 'note' => null, 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
            ['event_attendance_id' => 5, 'event_id' => 2, 'member_id' => 5, 'attendance_status_id' => 3, 'attendance_time' => null, 'note' => 'Izin tugas ke luar kota', 'is_deleted' => false, 'created_by' => 1, 'date_created' => $now],
        ];

        foreach ($eventAttendances as $ea) {
            EventAttendance::updateOrCreate(['event_attendance_id' => $ea['event_attendance_id']], $ea);
        }
    }
}
