<?php

namespace App\Console\Commands;

use App\Models\Configuracao;
use App\Models\Membro;
use App\Models\MensalidadeCota;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GerarMensalidadeCota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mensalidadescota:gerar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerar mensalidades por cota';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $membros = Membro::get();

        $configuracao = Configuracao::first();

        foreach ($membros as $item) {

            $mes = now()->month;
            $ano = now()->year;

            $existe = MensalidadeCota::where([
                'membro_id' => $item->id,
                'mes' => $mes,
                'ano' => $ano
            ])->exists();

            if (!$existe) {

                $vencimento = Carbon::create(
                    $ano,
                    $mes,
                    $configuracao ? (int) $configuracao->dia_limite_pagamento : 5
                );

                MensalidadeCota::create([
                    'membro_id' => $item->id,
                    'mes' => $mes,
                    'ano' => $ano,
                    'valor_original' => $configuracao ? $configuracao->valor_cota : 0,
                    'valor_total' =>  $configuracao ? $configuracao->valor_cota : 0,
                    'saldo_devedor' => $configuracao ? $configuracao->valor_cota : 0,
                    'data_vencimento' => $vencimento
                ]);
            }
        }
    }
}
