<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Cria permissões necessárias
        Permission::firstOrCreate(['name' => 'concluir_chamado', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'alter_tecnico_responsavel', 'guard_name' => 'web']);
    }
}
