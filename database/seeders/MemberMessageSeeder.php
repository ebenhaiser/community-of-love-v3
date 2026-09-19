<?php

namespace Database\Seeders;

use App\Models\MemberMessage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class MemberMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $messages = [
            [
                'message_id' => 1,
                'cool_id' => 1,
                'member_id' => 1,
                'shepherd_id' => 1,
                'member_session_id' => null,
                'message' => 'Shalom Pak Gembala, mohon dukungan doa untuk ibu saya yang sedang dirawat di RS Carolus. Terima kasih banyak Pak.',
                'status' => 'UNREAD',
                'read_at' => null,
                'is_deleted' => false,
                'date_created' => $now->copy()->subHours(2),
            ],
            [
                'message_id' => 2,
                'cool_id' => 1,
                'member_id' => 3,
                'shepherd_id' => 1,
                'member_session_id' => null,
                'message' => 'Shalom Ps. Budi, saya rindu ikut melayani sebagai worship leader di COOL, mohon arahan dan bimbingannya ya Pak.',
                'status' => 'READ',
                'read_at' => $now->copy()->subHours(12),
                'is_deleted' => false,
                'date_created' => $now->copy()->subDay(),
            ],
        ];

        foreach ($messages as $message) {
            MemberMessage::updateOrCreate(['message_id' => $message['message_id']], $message);
        }
    }
}
