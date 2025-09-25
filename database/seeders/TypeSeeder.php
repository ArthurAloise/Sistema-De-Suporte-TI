<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $typeMap = [
//            'Problema de rede'  => ['muito alta', 4],
//            'Acesso ao Sistema' => ['muito alta', 4],
//            'Bug no sistema'    => ['alta', 8],
//            'Sistema'           => ['alta', 8],
//            'E-mail'            => ['alta', 8],
//            'Backup'            => ['media', 24],
//            'Acesso Wi-Fi'      => ['media', 24],
//            'Impressão'         => ['baixa', 72],
//            'Outros'            => [null, null],
//        ];
//        foreach ($typeMap as $nome => [$prio, $h]) {
//            Type::updateOrCreate(
//                ['nome' => $nome],
//                ['default_priority' => $prio, 'sla_hours' => $h]
//            );
//        }
        // mapa: nome do TYPE => [categoria, prioridade_default, sla_hours]
        $typeMap = [
            'Problema de rede'  => ['Rede',           'muito alta', 4],
            'Acesso ao Sistema' => ['Acesso/Conta',   'muito alta', 4],
            'Bug no sistema'    => ['Software',       'alta',       8],
            'Sistema'           => ['Software',       'alta',       8],
            'E-mail'            => ['Software',       'alta',       8], // ou 'Acesso/Conta' se preferir
            'Backup'            => ['Backup',         'media',      24],
            'Acesso Wi-Fi'      => ['Rede',           'media',      24],
            'Impressão'         => ['Impressoras',    'baixa',      72],
            'Outros'            => ['Outros',         null,         null],
        ];

        foreach ($typeMap as $typeNome => [$catNome, $prio, $h]) {
            // Busca (ou cria) a categoria pelo nome
            $category = Category::firstOrCreate(['nome' => $catNome], [
                'default_priority' => null,
                'sla_hours'        => null,
            ]);

            Type::updateOrCreate(
                ['category_id' => $category->id, 'nome' => $typeNome], // UNIQUE composto
                ['default_priority' => $prio, 'sla_hours' => $h]
            );
        }
    }
}
