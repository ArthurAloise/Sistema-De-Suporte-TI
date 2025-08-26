<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            //ADMIN
            'acessar_admin',
            'acessar_perfis',
            'acessar_permissoes',
            'acessar_painel_admin',
            //SUPORTE | ADMIN
            'alter_tecnico_responsavel',
            'concluir_chamado',
            'marcar_pendencias',
        ];

        $data = array_map(fn ($name) => [
            'name' => $name,
            'created_at' => now(),
            'updated_at' => now(),
        ], $permissions);

        Permission::upsert($data, ['name'], ['updated_at']);
    }
}
