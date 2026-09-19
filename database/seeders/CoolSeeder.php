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
                'shepherd_id' => 1,
                'description' => 'Kelompok COOL kaum senior dan keluarga dewasa.',
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
