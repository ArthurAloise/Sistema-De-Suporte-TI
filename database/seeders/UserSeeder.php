<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Setor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin demo
        $adminRoleId = Role::where('name', 'Admin')->value('id');
        $setorTI  = Setor::where('sigla', 'TI')->value('id');
        $fallbackSetorId = Setor::value('id');
        User::firstOrCreate(
            ['email' =>  'admin@gmail.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('12345678'),
                'phone'    => null,
                'role_id'  => $adminRoleId,
                'setor_id'          => $setorTI ?? $fallbackSetorId,
                'profile_picture' => null,
                'email_verified_at' => now(),
            ]
        );

        // Suporte demo
        $suporteRoleId = Role::where('name', 'Suporte')->value('id');
        User::firstOrCreate(
            ['email' => 'suporte@gmail.com'],
            [
                'name'     => 'Suporte',
                'password' => Hash::make('12345678'),
                'role_id'  => $suporteRoleId,
                'setor_id'          => $setorTI ?? $fallbackSetorId,
                'profile_picture' => null,
                'email_verified_at' => now(),
            ]
        );

        // Usuário demo
        $usuarioRoleId = Role::where('name', 'Usuario')->value('id');
        $setorRH  = Setor::where('sigla', 'RH')->value('id');
        User::firstOrCreate(
            ['email' => 'usuario@gmail.com'],
            [
                'name'     => 'Usuário',
                'password' => Hash::make('12345678'),
                'role_id'  => $usuarioRoleId,
                'setor_id'          => $setorRH ?? $fallbackSetorId,
                'profile_picture' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
