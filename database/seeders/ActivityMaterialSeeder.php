<?php

namespace Database\Seeders;

use App\Models\ActivityMaterial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ActivityMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $materials = [
            [
                'material_id' => 1,
                'activity_id' => 1,
                'material_type' => 'PDF',
                'file_name' => 'Bahan-COOL-Agustus-W1.pdf',
                'file_path' => 'materials/2026/08/Bahan-COOL-Agustus-W1.pdf',
                'external_url' => null,
                'file_size' => 1048576,
                'mime_type' => 'application/pdf',
                'description' => 'Outline khotbah dan bahan perenungan COOL minggu 1 Agustus.',
                'is_deleted' => false,
                'uploaded_by' => 2,
                'date_uploaded' => $now,
            ],
            [
                'material_id' => 2,
                'activity_id' => 1,
                'material_type' => 'EXTERNAL_LINK',
                'file_name' => null,
                'file_path' => null,
                'external_url' => 'https://youtube.com/watch?v=sample-worship',
                'file_size' => null,
                'mime_type' => null,
                'description' => 'Playlist lagu pujian dan penyembahan pembuka ibadah COOL.',
                'is_deleted' => false,
                'uploaded_by' => 2,
                'date_uploaded' => $now,
            ],
            [
                'material_id' => 3,
                'activity_id' => 5,
                'material_type' => 'PDF',
                'file_name' => 'Panduan-Sharing-Buah-Roh.pdf',
                'file_path' => 'materials/2026/09/Panduan-Sharing-Buah-Roh.pdf',
                'external_url' => null,
                'file_size' => 2097152,
                'mime_type' => 'application/pdf',
                'description' => 'Panduan pertanyaan sharing kelompok COOL tema Buah Roh.',
                'is_deleted' => false,
                'uploaded_by' => 2,
                'date_uploaded' => $now,
            ],
        ];

        foreach ($materials as $material) {
            ActivityMaterial::updateOrCreate(['material_id' => $material['material_id']], $material);
        }
    }
}
