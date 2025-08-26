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
        $itens = [
            'Bug no sistema',
            'Problema de rede',
            'Acesso Wi-Fi',
            'Backup',
            'Sistema',
            'Impressão',
            'E-mail',
            'Acesso ao Sistema',
            'Outros',
        ];

        // upsert por nome (único)
        $data = array_map(fn($n) => ['nome' => $n, 'created_at' => now(), 'updated_at' => now()], $itens);
        Type::upsert($data, ['nome'], ['updated_at']);
    }
}
