<?php

namespace App\Console\Commands;

use App\Models\Entidade;
use App\Models\Mensalidade;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GerarMensalidades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mensalidades:gerar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gerar mensalidades';

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
        $empresas = Entidade::with('plano')->get();

        foreach ($empresas as $empresa) {

            $mes = now()->month;
            $ano = now()->year;

            $existe = Mensalidade::where([
                'entidade_id' => $empresa->id,
                'mes' => $mes,
                'ano' => $ano
            ])->exists();

            if (!$existe) {

                $vencimento = Carbon::create(
                    $ano,
                    $mes,
                    $empresa->plano ? (int) $empresa->plano->dia_vencimento : 1
                );

                Mensalidade::create([
                    'entidade_id' => $empresa->id,
                    'mes' => $mes,
                    'ano' => $ano,
                    'valor_original' => $empresa->plano ? $empresa->plano->valor_mensal : 0,
                    'valor_total' =>  $empresa->plano ? $empresa->plano->valor_mensal : 0,
                    'saldo_devedor' => $empresa->plano ? $empresa->plano->valor_mensal : 0,
                    'data_vencimento' => $vencimento
                ]);
            }
        }
    }
}
