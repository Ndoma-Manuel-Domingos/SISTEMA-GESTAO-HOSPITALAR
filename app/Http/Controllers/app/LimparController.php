<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Atendimento;
use App\Models\CatalogoExame\ExameCatalogo;
use App\Models\CatalogoExame\ExameParametroCatalogo;
use App\Models\CatalogoExame\ExameSubParametroCatalogo;
use App\Models\Cliente;
use App\Models\ClienteContrato;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\ContaHospitalar;
use App\Models\ContaHospitalarItem;
use App\Models\ContaHospitalarMovimento;
use App\Models\ContaHospitalarPagamento;
use App\Models\DisponibilidadeMedica;
use App\Models\Estoque;
use App\Models\Exame;
use App\Models\ExameItem;
use App\Models\FacturaSeguradora;
use App\Models\FacturaSeguradoraConta;
use App\Models\FichaTriagem;
use App\Models\Internamento;
use App\Models\ItemVenda;
use App\Models\Lote;
use App\Models\Movimento;
use App\Models\MovimentoContaCliente;
use App\Models\OperacaoFinanceiro;
use App\Models\ParamentroConsulta;
use App\Models\PlanoInternamento;
use App\Models\SubParamentroExame;
use App\Models\PlanoTratamento;
use App\Models\Produto;
use App\Models\ReceitaMedica;
use App\Models\ReceitaMedicaItem;
use App\Models\Registro;
use App\Models\RegistroMovimento;
use App\Models\RegistroMovimentoItem;
use App\Models\ResultadoConsulta;
use App\Models\ResultadoConsultaParamentro;
use App\Models\ResultadoConsultaParamentroImagem;
use App\Models\ResultadoExame;
use App\Models\ResultadoExameParamentro;
use App\Models\ResultadoExameSubParamentro;
use App\Models\ResultadoExameSubParamentroImagem;
use App\Models\Seguradora;
use App\Models\SeguradoraPlano;
use App\Models\SeguradoraPlanoBeneficiador;
use App\Models\SeguradoraPlanoCobertura;
use App\Models\SessaoTratamento;
use App\Models\SolicitacaoMedica;
use App\Models\SolicitacaoMedicaItem;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LimparController extends Controller
{

    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        DisponibilidadeMedica::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ContaHospitalar::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ContaHospitalarItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ContaHospitalarMovimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ContaHospitalarPagamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Internamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        PlanoInternamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SessaoTratamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoExame::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Seguradora::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SeguradoraPlano::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        FacturaSeguradora::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        FacturaSeguradoraConta::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SeguradoraPlanoCobertura::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SeguradoraPlanoBeneficiador::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ParamentroConsulta::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();

        SubParamentroExame::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoExameParamentro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoExameSubParamentro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoExameSubParamentroImagem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();

        ResultadoConsulta::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoConsultaParamentro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ResultadoConsultaParamentroImagem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        PlanoTratamento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Atendimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        FichaTriagem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Consulta::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ConsultaItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Exame::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ExameItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        OperacaoFinanceiro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Movimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        RegistroMovimento::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        RegistroMovimentoItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();


        Lote::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Produto::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Estoque::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Registro::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SolicitacaoMedica::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        SolicitacaoMedicaItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ReceitaMedica::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ReceitaMedicaItem::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Venda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ItemVenda::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        Cliente::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        ClienteContrato::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
        MovimentoContaCliente::withTrashed()->where('entidade_id', $entidade->empresa->id)->forceDelete();
    }


    public function catalogoExame()
    {

        $dadosExame = [];

        dd($dadosExame);

        // Criar exame
        $exame = ExameCatalogo::create([
            'nome' => $dadosExame['nome'],
            'categoria' => $dadosExame['categoria'],
            'codigo' => $dadosExame['codigo'],
        ]);

        // Criar parâmetros
        foreach ($dadosExame['parametros'] as $parametroDados) {
            $parametro = ExameParametroCatalogo::create([

                'exame_id' => $exame->id,
                'nome' => $parametroDados['nome'],
                'ordem' => $parametroDados['ordem']

            ]);
            // Criar subparâmetros
            foreach ($parametroDados['subparametros'] as $subDados) {
                ExameSubParametroCatalogo::create([
                    'exame_id' => $exame->id,
                    'parametro_id' => $parametro->id,
                    'tipo' => $subDados['tipo'],
                    'nome' => $subDados['nome'],
                    'unidade' => $subDados['unidade'] ?? null,
                    'valor_referencia' => $subDados['valor_referencia'] ?? null,
                    'valor_minimo' => $subDados['valor_minimo'] ?? null,
                    'valor_maximo' => $subDados['valor_maximo'] ?? null,
                    'opcoes' => $subDados['opcoes'] ?? null,
                    'texto_sim' => $subDados['texto_sim'] ?? null,
                    'texto_nao' => $subDados['texto_nao'] ?? null,
                    'extensoes_permitidas' => $subDados['extensoes_permitidas'] ?? null,
                    'permitir_futuro' => $subDados['permitir_futuro'] ?? false,
                    'permitir_passado' => $subDados['permitir_passado'] ?? false,
                    'tamanho_maximo' => $subDados['tamanho_maximo'] ?? null,
                ]);
            }
        }


        dd("SUCESSO");
    }
}
