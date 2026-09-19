<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $roles = [
            [
                'role_id' => 1,
                'name' => 'MASTER',
                'description' => 'Master / Admin Aplikasi - Akses penuh seluruh sistem',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'role_id' => 2,
                'name' => 'SHEPHERD',
                'description' => 'Gembala COOL - Akses data kelompok COOL binaan',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'role_id' => 3,
                'name' => 'MEMBER',
                'description' => 'Anggota COOL - Akses portal anggota (Phase 2)',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['role_id' => $role['role_id']], $role);
        }
    }
}
