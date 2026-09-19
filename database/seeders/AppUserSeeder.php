<?php

namespace Database\Seeders;

use App\Models\User;
use App\Helpers\AppHelper;
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
        $_defaultPassword = AppHelper::getSettings('default_user_password');
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
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['user_id' => $userData['user_id']], $userData);
        }
    }
}
