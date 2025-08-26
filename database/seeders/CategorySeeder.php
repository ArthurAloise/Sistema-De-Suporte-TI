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
        $itens = [
            'Software',
            'Hardware',
            'Infraestrutura',
            'Rede',
            'Segurança',
            'Backup',
            'Acesso/Conta',
            'Telefonia',
            'Impressoras',
            'Outros',
        ];

        $data = array_map(fn($n) => ['nome' => $n, 'created_at' => now(), 'updated_at' => now()], $itens);
        Category::upsert($data, ['nome'], ['updated_at']);
    }
}
