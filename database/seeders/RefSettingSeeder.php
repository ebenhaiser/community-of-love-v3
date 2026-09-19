<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RefSetting;

class RefSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'name' => 'default_qr_pin',
                'type' => 'string',
                'value' => '123456',
            ],
            [
                'name' => 'default_user_password',
                'type' => 'string',
                'value' => '123456',
            ],
            [
                'name' => 'url_prefix',
                'type' => 'string',
                'value' => 'https://http://127.0.0.1:8000/',
            ]
        ];
        foreach ($settings as $setting) {
            RefSetting::create($setting);
        }
    }
}
