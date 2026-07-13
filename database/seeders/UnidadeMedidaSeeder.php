<?php

namespace Database\Seeders;

use App\Models\Unidade;
use Illuminate\Database\Seeder;

class UnidadeMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unidade::insert([
            [
                'nome'=>'Grama',
                'sigla'=>'g',
                'tipo'=>'peso',
                'fator_conversao'=>1
            ],
            [
                'nome'=>'Quilograma',
                'sigla'=>'kg',
                'tipo'=>'peso',
                'fator_conversao'=>1000
            ],
            [
                'nome'=>'Mililitro',
                'sigla'=>'ml',
                'tipo'=>'volume',
                'fator_conversao'=>1
            ],
            [
                'nome'=>'Litro',
                'sigla'=>'l',
                'tipo'=>'volume',
                'fator_conversao'=>1000
            ],
            [
                'nome'=>'Unidade',
                'sigla'=>'un',
                'tipo'=>'quantidade',
                'fator_conversao'=>1
            ],
            [
                'nome'=>'Dúzia',
                'sigla'=>'dz',
                'tipo'=>'quantidade',
                'fator_conversao'=>12
            ]
        ]);
    }
}
