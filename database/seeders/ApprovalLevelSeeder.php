<?php

namespace Database\Seeders;

use App\Models\ApprovalLevel;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ApprovalLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'level' => 1,
                'role' => 'spv_gudang',
                'name' => 'SPV Gudang',
            ],
            [
                'level' => 2,
                'role' => 'kepala_gudang',
                'name' => 'Kepala Gudang',
            ],
            [
                'level' => 3,
                'role' => 'manager_operasional',
                'name' => 'Manager Operasional',
            ],
            [
                'level' => 4,
                'role' => 'direktur_operasional',
                'name' => 'Direktur Operasional',
            ],
            [
                'level' => 5,
                'role' => 'direktur_keuangan',
                'name' => 'Direktur Keuangan',
            ],
        ];

        foreach ($levels as $data) {
            $role = Role::where('slug', $data['role'])->firstOrFail();

            ApprovalLevel::updateOrCreate(
                ['level' => $data['level']],
                [
                    'role_id' => $role->id,
                    'name' => $data['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}