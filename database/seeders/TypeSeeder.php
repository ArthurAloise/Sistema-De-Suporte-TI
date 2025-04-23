<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::insert([ // Exemplo: Bug no sistema, sistema, problema de rede, acessp de wifi, backup
            ['nome' => 'Sistema'],
            ['nome' => 'Bug de Distema'],
            ['nome' => 'Problema de Rede'],
            ['nome' => 'Acesso de Wifi'],
            ['nome' => 'Backup de Dados '],
        ]);
    }
}
