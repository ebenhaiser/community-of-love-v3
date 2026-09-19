<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RefSettingSeeder::class,
            RoleSeeder::class,
            AttendanceStatusSeeder::class,
            ActivityTypeSeeder::class,
            ShepherdSeeder::class,
            AppUserSeeder::class,
            CoolSeeder::class,
            MemberSeeder::class,
            CoolMemberSeeder::class,
            ActivitySeeder::class,
            AttendanceSeeder::class,
            ActivityMaterialSeeder::class,
            QrAccessSeeder::class,
            MemberMessageSeeder::class,
            NotificationSeeder::class,
            ChurchEventSeeder::class,
        ]);
    }
}
