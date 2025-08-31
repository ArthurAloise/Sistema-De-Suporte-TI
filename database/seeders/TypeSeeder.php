<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeMap = [
            'Problema de rede'  => ['muito alta', 4],
            'Acesso ao Sistema' => ['muito alta', 4],
            'Bug no sistema'    => ['alta', 8],
            'Sistema'           => ['alta', 8],
            'E-mail'            => ['alta', 8],
            'Backup'            => ['media', 24],
            'Acesso Wi-Fi'      => ['media', 24],
            'Impressão'         => ['baixa', 72],
            'Outros'            => [null, null],
        ];
        foreach ($typeMap as $nome => [$prio, $h]) {
            Type::updateOrCreate(
                ['nome' => $nome],
                ['default_priority' => $prio, 'sla_hours' => $h]
            );
        }
    }
}
