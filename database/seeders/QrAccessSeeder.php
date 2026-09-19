<?php

namespace Database\Seeders;

use App\Helpers\AppHelper;
use App\Models\QrAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class QrAccessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $defaultPinHash = AppHelper::getSettings('default_qr_pin') ?? 'password123';
        $defaultPinHash = Hash::make($defaultPinHash);

        $qrAccesses = [
            [
                'qr_access_id' => 1,
                'cool_id' => 1,
                'access_code' => 'COOL-SLM-001-QR',
                'pin_hash' => $defaultPinHash,
                'qr_token' => 'qr_slm_001_live_token_77a8',
                'expires_at' => Carbon::now()->addMonths(6),
                'last_used_at' => $now,
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 2,
                'date_created' => $now,
            ],
            [
                'qr_access_id' => 2,
                'cool_id' => 2,
                'access_code' => 'COOL-SLM-002-QR',
                'pin_hash' => $defaultPinHash,
                'qr_token' => 'qr_slm_002_live_token_99b2',
                'expires_at' => Carbon::now()->addMonths(6),
                'last_used_at' => $now,
                'is_active' => true,
                'is_deleted' => false,
                'created_by' => 3,
                'date_created' => $now,
            ],
        ];

        foreach ($qrAccesses as $access) {
            QrAccess::updateOrCreate(['qr_access_id' => $access['qr_access_id']], $access);
        }
    }
}
