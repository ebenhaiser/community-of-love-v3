<?php

namespace Database\Seeders;

use App\Models\Cool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $cools = [
            [
                'cool_id' => 1,
                'cool_code' => 'COOL-SLM-001',
                'name' => 'COOL Salemba 01 - Youth & Pro',
                'shepherd_id' => 1,
                'description' => 'Kelompok COOL pemuda dan profesional muda GBI Salemba.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'cool_id' => 2,
                'cool_code' => 'COOL-SLM-002',
                'name' => 'COOL Salemba 02 - Family Grace',
                'shepherd_id' => 2,
                'description' => 'Kelompok COOL keluarga muda dan pasangan pasutri.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'cool_id' => 3,
                'cool_code' => 'COOL-SLM-003',
                'name' => 'COOL Salemba 03 - Joyful Elders',
                'shepherd_id' => 3,
                'description' => 'Kelompok COOL kaum senior dan keluarga dewasa.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'cool_id' => 4,
                'cool_code' => 'COOL-SLM-004',
                'name' => 'COOL Salemba 04 - Campus Fire',
                'shepherd_id' => 5,
                'description' => 'Kelompok COOL mahasiswa dan pelajar di sekitar Salemba & Menteng.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'cool_id' => 5,
                'cool_code' => 'COOL-SLM-005',
                'name' => 'COOL Salemba 05 - Kingdom Builders',
                'shepherd_id' => 1,
                'description' => 'Kelompok COOL usahawan, pebisnis, dan eksekutif Kristen.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'cool_id' => 6,
                'cool_code' => 'COOL-SLM-006',
                'name' => 'COOL Salemba 06 - Women of Faith',
                'shepherd_id' => 4,
                'description' => 'Komunitas COOL persekutuan wanita dan ibu-ibu jemaat GBI Salemba.',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
        ];

        foreach ($cools as $cool) {
            Cool::updateOrCreate(['cool_id' => $cool['cool_id']], $cool);
        }
    }
}
