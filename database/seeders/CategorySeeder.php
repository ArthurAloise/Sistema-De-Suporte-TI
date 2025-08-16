<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = ['Rede', 'Hardware', 'Software', 'Banco de Dados'];

        foreach ($categorias as $nome) {
            Category::firstOrCreate(['nome' => $nome]);
        }
    }
}
