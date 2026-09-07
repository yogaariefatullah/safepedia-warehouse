<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Budi Requestor',
                'email' => 'requestor@safepedia.test',
                'role' => 'requestor',
            ],
            [
                'name' => 'Andi SPV Gudang',
                'email' => 'spv@safepedia.test',
                'role' => 'spv_gudang',
            ],
            [
                'name' => 'Siti Kepala Gudang',
                'email' => 'kepala.gudang@safepedia.test',
                'role' => 'kepala_gudang',
            ],
            [
                'name' => 'Rudi Manager Operasional',
                'email' => 'manager.operasional@safepedia.test',
                'role' => 'manager_operasional',
            ],
            [
                'name' => 'Dewi Direktur Operasional',
                'email' => 'direktur.operasional@safepedia.test',
                'role' => 'direktur_operasional',
            ],
            [
                'name' => 'Agus Direktur Keuangan',
                'email' => 'direktur.keuangan@safepedia.test',
                'role' => 'direktur_keuangan',
            ],
        ];

        foreach ($users as $data) {
            $role = Role::where('slug', $data['role'])->firstOrFail();

            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'role_id' => $role->id,
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'two_factor_secret' => null,
                    'two_factor_enabled' => false,
                ]
            );
        }
    }
}
