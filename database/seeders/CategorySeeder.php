<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catMap = [
            'Rede'           => ['muito alta', 4],
            'Infraestrutura' => ['muito alta', 4],
            'Segurança'      => ['alta', 8],
            'Software'       => ['alta', 8],
            'Acesso/Conta'   => ['alta', 8],
            'Hardware'       => ['media', 24],
            'Telefonia'      => ['media', 24],
            'Backup'         => ['media', 24],
            'Impressoras'    => ['baixa', 72],
            'Outros'         => [null, null],
        ];
        foreach ($catMap as $nome => [$prio, $h]) {
            Category::updateOrCreate(
                ['nome' => $nome],
                ['default_priority' => $prio, 'sla_hours' => $h]
            );
        }
    }
}
