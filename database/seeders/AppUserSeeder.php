<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AppUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $_defaultPassword = AppHelper::getSettings('default_user_password') ?? 'password123';
        $defaultPassword = Hash::make($_defaultPassword);

        $users = [
            [
                'user_id' => 1,
                'role_id' => 1,
                'shepherd_id' => null,
                'username' => 'master',
                'password_hash' => $defaultPassword,
                'full_name' => 'Master Administrator',
                'email' => 'master@gbisalemba.org',
                'phone' => '081100000001',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'user_id' => 2,
                'role_id' => 2,
                'shepherd_id' => 1,
                'username' => 'gembala.budi',
                'password_hash' => $defaultPassword,
                'full_name' => 'Ps. Budi Santoso',
                'email' => 'budi.santoso@gbisalemba.org',
                'phone' => '081234567890',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'user_id' => 3,
                'role_id' => 2,
                'shepherd_id' => 2,
                'username' => 'gembala.hendra',
                'password_hash' => $defaultPassword,
                'full_name' => 'Ps. Hendra Wijaya',
                'email' => 'hendra.wijaya@gbisalemba.org',
                'phone' => '081234567891',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'user_id' => 4,
                'role_id' => 2,
                'shepherd_id' => 3,
                'username' => 'gembala.yohanes',
                'password_hash' => $defaultPassword,
                'full_name' => 'Ps. Yohanes Surya',
                'email' => 'yohanes.surya@gbisalemba.org',
                'phone' => '081234567892',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'user_id' => 5,
                'role_id' => 2,
                'shepherd_id' => 4,
                'username' => 'gembala.maria',
                'password_hash' => $defaultPassword,
                'full_name' => 'Ps. Maria Magdalena',
                'email' => 'maria.magdalena@gbisalemba.org',
                'phone' => '081234567893',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'user_id' => 6,
                'role_id' => 2,
                'shepherd_id' => 5,
                'username' => 'gembala.andreas',
                'password_hash' => $defaultPassword,
                'full_name' => 'Ps. Andreas Setiawan',
                'email' => 'andreas.setiawan@gbisalemba.org',
                'phone' => '081234567894',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['user_id' => $userData['user_id']], $userData);
        }
    }
}
