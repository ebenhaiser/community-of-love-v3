<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $activities = [
            // COOL 1 Activities
            [
                'activity_id' => 1,
                'cool_id' => 1,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL - Kasih yang Mengubahkan',
                'activity_date' => '2026-08-07',
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Rumah Sdr. Andi - Salemba',
                'description' => 'Ibadah mingguan pembukaan bulan Agustus.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 2,
                'cool_id' => 1,
                'activity_type_id' => 2,
                'name' => 'Fellowship & Games - Bertumbuh Bersama',
                'activity_date' => '2026-08-14',
                'start_time' => '19:00:00',
                'end_time' => '21:30:00',
                'location' => 'Cafe Salemba Square',
                'description' => 'Makan malam bersama dan games keakraban kelompok.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 3,
                'cool_id' => 1,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL - Hidup dalam Kemenangan',
                'activity_date' => '2026-08-21',
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Rumah Sdr. Daniel',
                'description' => 'Ibadah dan pujian penyembahan.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 4,
                'cool_id' => 1,
                'activity_type_id' => 3,
                'name' => 'Doa Bersama & Sharing Firman',
                'activity_date' => '2026-08-28',
                'start_time' => '19:30:00',
                'end_time' => '21:00:00',
                'location' => 'Ruang Doa GBI Salemba Lt. 2',
                'description' => 'Doa syafaat untuk bangsa dan jemaat gereja.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 5,
                'cool_id' => 1,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL - Buah Roh Kudus',
                'activity_date' => '2026-09-04',
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Rumah Sdri. Citra',
                'description' => 'Pendalaman materi buah roh dalam keseharian.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 6,
                'cool_id' => 1,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL - Pengharapan yang Teguh',
                'activity_date' => '2026-09-11',
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Rumah Sdr. Andi - Salemba',
                'description' => 'Ibadah rutin COOL minggu kedua September.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'activity_id' => 7,
                'cool_id' => 1,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL - Melayani dengan Kasih',
                'activity_date' => '2026-09-25',
                'start_time' => '19:00:00',
                'end_time' => '21:00:00',
                'location' => 'Rumah Sdr. Budi',
                'description' => 'Ibadah akhir bulan dan evaluasi pelayanan.',
                'status' => 'SCHEDULED',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            // COOL 2 Activities
            [
                'activity_id' => 8,
                'cool_id' => 2,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL Keluarga - Membangun Mezbah Keluarga',
                'activity_date' => '2026-08-15',
                'start_time' => '18:00:00',
                'end_time' => '20:30:00',
                'location' => 'Kediaman Bpk. Fransiscus',
                'description' => 'Ibadah keluarga pasutri dan anak-anak.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'activity_id' => 9,
                'cool_id' => 2,
                'activity_type_id' => 2,
                'name' => 'Fellowship Keluarga - Potluck & Sharing',
                'activity_date' => '2026-08-29',
                'start_time' => '18:00:00',
                'end_time' => '21:00:00',
                'location' => 'Taman Perumahan Salemba',
                'description' => 'Potluck dinner dan fellowship ramah anak.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'activity_id' => 10,
                'cool_id' => 2,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL Keluarga - Berakar dan Berbuah',
                'activity_date' => '2026-09-12',
                'start_time' => '18:00:00',
                'end_time' => '20:30:00',
                'location' => 'Kediaman Bpk. Handy',
                'description' => 'Ibadah keluarga September.',
                'status' => 'COMPLETED',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'activity_id' => 11,
                'cool_id' => 2,
                'activity_type_id' => 1,
                'name' => 'Ibadah COOL Keluarga - Menjadi Terang di Komunitas',
                'activity_date' => '2026-09-26',
                'start_time' => '18:00:00',
                'end_time' => '20:30:00',
                'location' => 'Kediaman Ibu Grace',
                'description' => 'Ibadah keluarga penutup bulan September.',
                'status' => 'SCHEDULED',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
        ];

        foreach ($activities as $activity) {
            Activity::updateOrCreate(['activity_id' => $activity['activity_id']], $activity);
        }
    }
}
