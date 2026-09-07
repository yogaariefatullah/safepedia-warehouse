<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Requestor',
                'slug' => 'requestor',
                'description' => 'User yang membuat pengajuan pembangunan gudang.',
            ],
            [
                'name' => 'SPV Gudang',
                'slug' => 'spv_gudang',
                'description' => 'Melakukan review awal pengajuan.',
            ],
            [
                'name' => 'Kepala Gudang',
                'slug' => 'kepala_gudang',
                'description' => 'Melakukan validasi kebutuhan dan kelengkapan dokumen.',
            ],
            [
                'name' => 'Manager Operasional',
                'slug' => 'manager_operasional',
                'description' => 'Meninjau aspek operasional, lokasi, dan budget.',
            ],
            [
                'name' => 'Direktur Operasional',
                'slug' => 'direktur_operasional',
                'description' => 'Memberikan persetujuan strategis dan kelayakan operasional.',
            ],
            [
                'name' => 'Direktur Keuangan',
                'slug' => 'direktur_keuangan',
                'description' => 'Memberikan persetujuan akhir terkait budget dan kelayakan finansial.',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
