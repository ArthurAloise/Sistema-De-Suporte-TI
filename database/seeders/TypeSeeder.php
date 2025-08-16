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
Type::firstOrCreate(['nome' => 'Sistema']);
Type::firstOrCreate(['nome' => 'Bug de Distema']);
Type::firstOrCreate(['nome' => 'Problema de Rede']);
Type::firstOrCreate(['nome' => 'Acesso de Wifi']);
Type::firstOrCreate(['nome' => 'Backup de Dados']);
    }
}
