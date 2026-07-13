<?php

namespace App\Console\Commands;

use App\Models\Configuracao;
use App\Models\MensalidadeCota;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AtualizarJurosCota extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mensalidadescota:juros';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualizar multas e juros das cotas';

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
        $configuracao = Configuracao::first();

        $mensalidades = MensalidadeCota::where('status', '!=', 'pago')
            ->get();

        foreach ($mensalidades as $mensalidade) {

            if (now()->gt($mensalidade->data_vencimento)) {

                $dias = Carbon::parse($mensalidade->data_vencimento)->diffInDays(now());

                $mensalidade->dias_atraso = $dias;

                if ($mensalidade->multa == 0) {
                    $mensalidade->multa = ($mensalidade->valor_original  * ($configuracao ? $configuracao->multa_percentual : 0)) / 100;
                }

                $mensalidade->juros = (($mensalidade->valor_original * ($configuracao ? $configuracao->juros_diario : 0)) / 100) * $dias;

                $mensalidade->valor_total = $mensalidade->valor_original + $mensalidade->multa + $mensalidade->juros;
                $mensalidade->saldo_devedor = $mensalidade->valor_total - $mensalidade->valor_pago;
                $mensalidade->status = 'vencido';
                $mensalidade->save();
            }
        }
    }
}
