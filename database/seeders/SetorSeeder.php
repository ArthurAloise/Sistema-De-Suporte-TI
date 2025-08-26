<?php

namespace Database\Seeders;

use App\Models\Setor;
use Illuminate\Database\Seeder;

class SetorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $itens = [
            ['nome' => 'Tecnologia da Informação', 'sigla' => 'TI'],
            ['nome' => 'Recursos Humanos',          'sigla' => 'RH'],
            ['nome' => 'Diretoria de Sistemas',     'sigla' => 'DSI'],
            ['nome' => 'Financeiro',                'sigla' => 'FIN'],
            ['nome' => 'Suprimentos',               'sigla' => 'SUP'],
            ['nome' => 'Jurídico',                  'sigla' => 'JUR'],
            ['nome' => 'Comunicação',               'sigla' => 'COM'],
        ];

        // upsert por sigla (única) e atualiza nome se mudar
        $data = array_map(function ($i) {
            return [
                'sigla'      => mb_strtoupper(trim($i['sigla']), 'UTF-8'),
                'nome'       => $i['nome'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $itens);

        Setor::upsert($data, ['sigla'], ['nome','updated_at']);
    }
}
