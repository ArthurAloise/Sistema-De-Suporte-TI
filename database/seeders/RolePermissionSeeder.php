<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // mapa de permissões por perfil
        $map = [
            'Admin' => [
                'acessar_admin',
                'acessar_perfis',
                'acessar_permissoes',
                'acessar_painel_admin',
                'alter_tecnico_responsavel',
                'concluir_chamado',
                'marcar_pendencias',
            ],
            'Suporte' => [
                'acessar_painel_admin',
                'alter_tecnico_responsavel',
                'concluir_chamado',
                'marcar_pendencias',
            ],
            'Usuario' => [
                // normalmente usuário final não precisa de permissões especiais
            ],
        ];

        foreach ($map as $roleName => $permNames) {
            $role = Role::where('name', $roleName)->first();
            if (!$role) continue;

            $permIds = Permission::whereIn('name', $permNames)->pluck('id')->all();
            $role->permissions()->syncWithoutDetaching($permIds);
        }
    }
}
