<?php

namespace Database\Seeders;

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'activity_type_id' => 1,
                'name' => 'IBADAH',
                'description' => 'Ibadah rutin kelompok COOL',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'activity_type_id' => 2,
                'name' => 'FELLOWSHIP',
                'description' => 'Persekutuan, makan bersama, dan keakraban kelompok',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'activity_type_id' => 3,
                'name' => 'DOA',
                'description' => 'Doa dan puasa bersama anggota COOL',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'activity_type_id' => 4,
                'name' => 'SHARING',
                'description' => 'Sesi sharing kesaksian dan pendalaman firman Tuhan',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'activity_type_id' => 5,
                'name' => 'SOSIAL',
                'description' => 'Aksi sosial dan pelayanan kasih kepada sesama',
                'is_active' => true,
                'is_deleted' => false,
            ],
            [
                'activity_type_id' => 6,
                'name' => 'LAINNYA',
                'description' => 'Kegiatan COOL lainnya',
                'is_active' => true,
                'is_deleted' => false,
            ],
        ];

        foreach ($types as $type) {
            ActivityType::updateOrCreate(['activity_type_id' => $type['activity_type_id']], $type);
        }
    }
}
