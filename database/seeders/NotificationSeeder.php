<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $notifications = [
            [
                'notification_id' => 1,
                'user_id' => 2,
                'notification_type' => 'NEW_MESSAGE',
                'title' => 'Pesan Baru dari Anggota',
                'message' => 'Ada pesan baru dari Andi Pratama (COOL Salemba 01 - Youth & Pro).',
                'reference_type' => 'MEMBER_MESSAGE',
                'reference_id' => 1,
                'read_at' => null,
                'is_deleted' => false,
                'date_created' => $now->copy()->subHours(2),
            ],
            [
                'notification_id' => 2,
                'user_id' => 2,
                'notification_type' => 'ACTIVITY_REMINDER',
                'title' => 'Pengingat Kegiatan COOL',
                'message' => 'Jadwal kegiatan Ibadah COOL - Melayani dengan Kasih akan dilaksanakan minggu depan.',
                'reference_type' => 'ACTIVITY',
                'reference_id' => 7,
                'read_at' => $now->copy()->subDays(1),
                'is_deleted' => false,
                'date_created' => $now->copy()->subDays(2),
            ],
        ];

        foreach ($notifications as $notification) {
            Notification::updateOrCreate(['notification_id' => $notification['notification_id']], $notification);
        }
    }
}
