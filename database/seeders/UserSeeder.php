<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin demo
        $adminRoleId = Role::where('name', 'Admin')->value('id');
        User::firstOrCreate(
            ['email' =>  'admin@gmail.com'],
            [
                'name'     => 'Administrador',
                'password' => Hash::make('12345678'),
                'phone'    => null,
                'role_id'  => $adminRoleId,
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
                'profile_picture' => null,
                'email_verified_at' => now(),
            ]
        );

        // Usuário demo
        $usuarioRoleId = Role::where('name', 'Usuario')->value('id');
        User::firstOrCreate(
            ['email' => 'usuario@gmail.com'],
            [
                'name'     => 'Usuário',
                'password' => Hash::make('12345678'),
                'role_id'  => $usuarioRoleId,
                'profile_picture' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
