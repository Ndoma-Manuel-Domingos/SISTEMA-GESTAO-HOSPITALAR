<?php

namespace App\Services;

use App\Models\Atendimento;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\Prioridade;
use App\Models\Produto;
use App\Models\ResultadoConsulta;
use App\Models\ResultadoConsultaParamentro;
use App\Models\ResultadoConsultaParamentroImagem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ConsultorioService
{

    public function createConsultaAutomatic(Atendimento $origem, User $entidade)
    {
        $prioridade = Prioridade::findOrFail($origem->prioridade_id);

        try {
            if ($prioridade->nome === "Emergência" || $prioridade->nome === "Muito Urgente" || $prioridade->nome === "Urgente") {

                if (!Consulta::where("entidade_id", $entidade->entidade_id)->where('paciente_id', $origem->cliente_id)->where('atendimento_id', $origem->id)->first()) {

                    $consulta = Produto::where("entidade_id", $entidade->entidade_id)->where('nome', 'Consulta Geral')->first();

                    if ($consulta) {
                        $create = Consulta::create([
                            "data_consulta" => date("Y-m-d"),
                            "hora_consulta" => date("h:i:s"),
                            "paciente_id" => $origem->cliente_id,
                            "medico_id" => $origem->profissional_id,
                            "atendimento_id" => $origem->id,
                            "status" => "AGENDADA",
                            "pago" => "NAO PAGO",
                            "total" => $consulta->preco_venda,
                            "user_id" => Auth::user()->id,
                            "entidade_id" =>  $entidade->empresa->id,
                            "observacao" => NULL,
                            "movito_agendamento" => NULL,
                        ]);

                        $item = ConsultaItem::create([
                            'produto_id' => $consulta->id,
                            'consulta_id' => $create->id,
                            'valor' => $consulta->preco_venda,
                            'status' => "concluido",
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);

                        $resultado = ResultadoConsulta::create([
                            "consulta_id" => $create->id,
                            "status" => "processo",
                            "referencia" => "RESULT-" . time(),
                            "observacoes_resultado" => NULL,
                            "data_realizacao" => NULL,
                            "hora_realizacao" => NULL,
                            "user_id" => Auth::user()->id,
                            "entidade_id" =>  $entidade->empresa->id,
                        ]);

                        if ($item->produto->paramentros_consulta) {
                            foreach ($item->produto->paramentros_consulta as $paramentro) {
                                if ($paramentro->tipo == "imagem") {
                                    ResultadoConsultaParamentroImagem::create([
                                        "resultado_id" => $resultado->id,
                                        "parametro_id" => $paramentro->id,
                                        "ficheiro" => NULL,
                                        "descricao" => NULL,
                                        "ordem" => NULL,
                                        "item_consulta_id" => $item->id,
                                        "user_id" => Auth::user()->id,
                                        "entidade_id" =>  $entidade->empresa->id,
                                    ]);
                                }
                                ResultadoConsultaParamentro::create([
                                    "resultado_id" => $resultado->id,
                                    "parametro_id" => $paramentro->id,
                                    "valor" => NULL,
                                    "item_consulta_id" => $item->id,
                                    "user_id" => Auth::user()->id,
                                    "entidade_id" =>  $entidade->empresa->id,
                                ]);
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // opcional: registrar o erro
            Log::error('Erro ao criar consulta automática', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            // não lança novamente
        }
    }
}
