<?php

namespace Database\Seeders;

use App\Models\Shepherd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ShepherdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $shepherds = [
            [
                'shepherd_id' => 1,
                'name' => 'Ps. Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi.santoso@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'shepherd_id' => 2,
                'name' => 'Ps. Hendra Wijaya',
                'phone' => '081234567891',
                'email' => 'hendra.wijaya@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'shepherd_id' => 3,
                'name' => 'Ps. Yohanes Surya',
                'phone' => '081234567892',
                'email' => 'yohanes.surya@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'shepherd_id' => 4,
                'name' => 'Ps. Maria Magdalena',
                'phone' => '081234567893',
                'email' => 'maria.magdalena@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'shepherd_id' => 5,
                'name' => 'Ps. Andreas Setiawan',
                'phone' => '081234567894',
                'email' => 'andreas.setiawan@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
            [
                'shepherd_id' => 6,
                'name' => 'Ps. Ruth Damayanti',
                'phone' => '081234567895',
                'email' => 'ruth.damayanti@gbisalemba.org',
                'is_active' => true,
                'is_deleted' => false,
                'date_created' => $now,
            ],
        ];

        foreach ($shepherds as $shepherd) {
            Shepherd::updateOrCreate(['shepherd_id' => $shepherd['shepherd_id']], $shepherd);
        }
    }
}
