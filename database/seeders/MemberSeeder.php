<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $members = [
            [
                'member_id' => 1,
                'member_code' => 'MBR-001',
                'name' => 'Andi Pratama',
                'phone' => '081211110001',
                'email' => 'andi.pratama@example.com',
                'join_date' => '2025-01-10',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 2,
                'member_code' => 'MBR-002',
                'name' => 'Budi Gunawan',
                'phone' => '081211110002',
                'email' => 'budi.gunawan@example.com',
                'join_date' => '2025-01-15',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 3,
                'member_code' => 'MBR-003',
                'name' => 'Citra Lestari',
                'phone' => '081211110003',
                'email' => 'citra.lestari@example.com',
                'join_date' => '2025-02-01',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 4,
                'member_code' => 'MBR-004',
                'name' => 'Daniel Christian',
                'phone' => '081211110004',
                'email' => 'daniel.christian@example.com',
                'join_date' => '2025-02-10',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 5,
                'member_code' => 'MBR-005',
                'name' => 'Ester Natalia',
                'phone' => '081211110005',
                'email' => 'ester.natalia@example.com',
                'join_date' => '2025-03-01',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 6,
                'member_code' => 'MBR-006',
                'name' => 'Fransiscus Kevin',
                'phone' => '081211110006',
                'email' => 'fransiscus.kevin@example.com',
                'join_date' => '2025-03-15',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 7,
                'member_code' => 'MBR-007',
                'name' => 'Grace Angelia',
                'phone' => '081211110007',
                'email' => 'grace.angelia@example.com',
                'join_date' => '2025-04-01',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 8,
                'member_code' => 'MBR-008',
                'name' => 'Handy Kurniawan',
                'phone' => '081211110008',
                'email' => 'handy.kurniawan@example.com',
                'join_date' => '2025-04-10',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 9,
                'member_code' => 'MBR-009',
                'name' => 'Irene Susanto',
                'phone' => '081211110009',
                'email' => 'irene.susanto@example.com',
                'join_date' => '2025-05-01',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
            [
                'member_id' => 10,
                'member_code' => 'MBR-010',
                'name' => 'Jonathan Hartono',
                'phone' => '081211110010',
                'email' => 'jonathan.hartono@example.com',
                'join_date' => '2025-05-15',
                'status' => 'ACTIVE',
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 1,
                'date_created' => $now,
            ],
        ];

        foreach ($members as $member) {
            Member::updateOrCreate(['member_id' => $member['member_id']], $member);
        }
    }
}
