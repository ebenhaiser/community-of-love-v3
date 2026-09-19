<?php

namespace Database\Seeders;

use App\Models\CoolMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class CoolMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $coolMembers = [
            // COOL 1 members
            [
                'cool_member_id' => 1,
                'cool_id' => 1,
                'member_id' => 1,
                'start_date' => '2025-01-10',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 2,
                'cool_id' => 1,
                'member_id' => 2,
                'start_date' => '2025-01-15',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 3,
                'cool_id' => 1,
                'member_id' => 3,
                'start_date' => '2025-02-01',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 4,
                'cool_id' => 1,
                'member_id' => 4,
                'start_date' => '2025-02-10',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 5,
                'cool_id' => 1,
                'member_id' => 5,
                'start_date' => '2025-03-01',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            // COOL 2 members
            [
                'cool_member_id' => 6,
                'cool_id' => 2,
                'member_id' => 6,
                'start_date' => '2025-03-15',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 7,
                'cool_id' => 2,
                'member_id' => 7,
                'start_date' => '2025-04-01',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 8,
                'cool_id' => 2,
                'member_id' => 8,
                'start_date' => '2025-04-10',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 9,
                'cool_id' => 2,
                'member_id' => 9,
                'start_date' => '2025-05-01',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
            [
                'cool_member_id' => 10,
                'cool_id' => 2,
                'member_id' => 10,
                'start_date' => '2025-05-15',
                'end_date' => null,
                'status' => 'ACTIVE',
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
        ];

        foreach ($coolMembers as $item) {
            CoolMember::updateOrCreate(['cool_member_id' => $item['cool_member_id']], $item);
        }
    }
}
