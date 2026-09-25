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

        $assignments = [
            // COOL 1: Youth & Pro
            ['cool_id' => 1, 'member_id' => 1, 'start_date' => '2025-01-10', 'creator' => 2],
            ['cool_id' => 1, 'member_id' => 2, 'start_date' => '2025-01-15', 'creator' => 2],
            ['cool_id' => 1, 'member_id' => 3, 'start_date' => '2025-02-01', 'creator' => 2],
            ['cool_id' => 1, 'member_id' => 4, 'start_date' => '2025-02-10', 'creator' => 2],

            // COOL 2: Family Grace
            ['cool_id' => 2, 'member_id' => 5, 'start_date' => '2025-03-01', 'creator' => 3],
            ['cool_id' => 2, 'member_id' => 8, 'start_date' => '2025-04-10', 'creator' => 3],
            ['cool_id' => 2, 'member_id' => 10, 'start_date' => '2025-05-15', 'creator' => 3],
            ['cool_id' => 2, 'member_id' => 12, 'start_date' => '2025-06-15', 'creator' => 3],
            ['cool_id' => 2, 'member_id' => 14, 'start_date' => '2025-07-15', 'creator' => 3],

            // COOL 3: Joyful Elders
            ['cool_id' => 3, 'member_id' => 15, 'start_date' => '2025-08-01', 'creator' => 4],
            ['cool_id' => 3, 'member_id' => 16, 'start_date' => '2025-08-10', 'creator' => 4],
            ['cool_id' => 3, 'member_id' => 21, 'start_date' => '2025-11-01', 'creator' => 4],
            ['cool_id' => 3, 'member_id' => 22, 'start_date' => '2025-11-15', 'creator' => 4],

            // COOL 4: Campus Fire
            ['cool_id' => 4, 'member_id' => 6, 'start_date' => '2025-03-15', 'creator' => 6],
            ['cool_id' => 4, 'member_id' => 7, 'start_date' => '2025-04-01', 'creator' => 6],
            ['cool_id' => 4, 'member_id' => 11, 'start_date' => '2025-06-01', 'creator' => 6],
            ['cool_id' => 4, 'member_id' => 17, 'start_date' => '2025-09-01', 'creator' => 6],
            ['cool_id' => 4, 'member_id' => 18, 'start_date' => '2025-09-10', 'creator' => 6],
            ['cool_id' => 4, 'member_id' => 23, 'start_date' => '2025-12-01', 'creator' => 6],

            // COOL 5: Kingdom Builders
            ['cool_id' => 5, 'member_id' => 9, 'start_date' => '2025-05-01', 'creator' => 2],
            ['cool_id' => 5, 'member_id' => 13, 'start_date' => '2025-07-01', 'creator' => 2],
            ['cool_id' => 5, 'member_id' => 19, 'start_date' => '2025-10-01', 'creator' => 2],

            // COOL 6: Women of Faith
            ['cool_id' => 6, 'member_id' => 20, 'start_date' => '2025-10-15', 'creator' => 5],
            ['cool_id' => 6, 'member_id' => 24, 'start_date' => '2025-12-10', 'creator' => 5],
        ];

        foreach ($assignments as $index => $item) {
            $coolMemberId = $index + 1;
            CoolMember::updateOrCreate(
                ['cool_member_id' => $coolMemberId],
                [
                    'cool_id' => $item['cool_id'],
                    'member_id' => $item['member_id'],
                    'start_date' => $item['start_date'],
                    'end_date' => null,
                    'status' => 'ACTIVE',
                    'is_deleted' => false,
                    'created_by' => $item['creator'],
                    'date_created' => $now,
                ]
            );
        }
    }
}
