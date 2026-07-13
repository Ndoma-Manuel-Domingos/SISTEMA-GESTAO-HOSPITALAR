<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\ContaBancaria;
use App\Models\Devolucao;
use App\Models\Dispesa;
use App\Models\Estoque;
use App\Models\ItemDevolucao;
use App\Models\ItemVenda;
use App\Models\Loja;
use App\Models\OperacaoFinanceiro;
use App\Models\Produto;
use App\Models\Registro;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use phpseclib\Crypt\RSA;

class DevolucaoProdutoController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar facturas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.devolucoes.index', $head);
    }


    public function buscarFatura(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fatura = Venda::with(['items.produto'])->where('codigo_factura', $request->numero)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        if (!$fatura) {
            return response()->json(['erro' => 'Fatura não encontrada'], 404);
        }
        return response()->json($fatura);
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $contador = Devolucao::where('entidade_id', $entidade->empresa->id)->count();

            $factura = Venda::findOrFail($request->fatura_id);

            $loja = Loja::where("entidade_id", $entidade->entidade_id)->where("status", "activo")->first();

            $devolucao = Devolucao::create([
                'numero' => $contador + 1,
                'factura_id' => $factura->id,
                'motivo' => $request->motivo,
                'data_at' => date("Y-m-d"),
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);


            $privatekey = $this->pegarChavePrivada();
            $publickey = $this->pegarChavePublica();
            // 
            $rsa = new RSA(); //Algoritimo RSA
            // Lendo a private key
            $rsa->loadKey($privatekey);

            $totalValorBase = 0;
            $totalValorIva = 0;
            $totalItems = 0;
            $totalDesconto = 0;
            $totalRetencao = 0;
            $totalPagar = 0;
            $totalValorItem = 0;

            $lucro_total = 0;
            $custo_total = 0;

            foreach ($request->produtos as $item) {

                $produto = Produto::findOrFail($item['produto_id']);

                $update_item = ItemVenda::findOrFail($item['item_id']);

                $q = $update_item->quantidade -= $item['quantidade'];

                $newQuantid = $q;

                $desconto = ($produto->preco * $newQuantid) * (($update_item->desconto_aplicado ?? 0) / 100);

                $valorBase = $produto->preco * $newQuantid;
                // calculo do iva
                $valorIva = ($produto->taxa ?? 0) / 100 * $valorBase;

                $retencao_fonte = 0;

                $valor_ = $valorBase + $valorIva;

                if ($produto->tipo == "S") {
                    $retencao_fonte = $valor_ * ($entidade->empresa->taxa_retencao_fonte ?? 0) / 100;
                } else {
                    $retencao_fonte = 0;
                }

                $update_item->quantidade = $newQuantid;
                $update_item->quantidade_devolvida += $item['quantidade'];
                $update_item->valor_pagar = ($valor_ - $retencao_fonte) - $desconto;

                $update_item->retencao_fonte = $retencao_fonte;
                $update_item->desconto_aplicado = $update_item->desconto_aplicado;
                $update_item->desconto_aplicado_valor = $desconto;
                $update_item->custo = $produto->preco_custo;
                $update_item->lucro = ($produto->preco_venda - $produto->preco_custo) * $newQuantid;

                $update_item->valor_base = $valorBase;
                $update_item->valor_iva = $valorIva;
                $update_item->update();

                $verificarEstoque_ = Estoque::where("entidade_id", $entidade->empresa->id)
                    ->where("produto_id", $item['produto_id'])
                    ->where("loja_id", $loja ? $loja->id : "")
                    ->first();

                if ($verificarEstoque_) {
                    $update = Estoque::findOrFail($verificarEstoque_->id);
                    $update->stock = $update->stock + $item['quantidade'];
                    $update->update();
                }

                $code = uniqid(time());
                $dispesa = Dispesa::where('type', 'D')->where('nome', 'Reembolso')->where('entidade_id', $entidade->empresa->id)->first();

                if ($factura->pagamento == "NU") {
                    $conta = Caixa::where('entidade_id', $entidade->empresa->id)
                        ->where('status_admin', 'liberado')->first();
                    $f = "C";
                } else {
                    $f = "B";
                    $conta = ContaBancaria::where('entidade_id', $entidade->empresa->id)->first();
                }

                $caixaActivo = Caixa::where('active', true)
                    ->where('status', 'aberto')
                    ->where('status_admin', 'liberado')
                    ->where('user_open_id', Auth::user()->id)
                    ->where('entidade_id', $entidade->empresa->id)
                    ->first();

                OperacaoFinanceiro::create([
                    'nome' => $dispesa->nome,
                    'status' => "pago",
                    'formas' => $f,
                    'motante' => $produto->preco_venda,
                    'subconta_id' => $conta->subconta_id,
                    'fornecedor_id' => $factura->cliente_id,
                    'model_id' => $dispesa->id,
                    'type' => "D",
                    'parcelado' => "N",
                    'status_pagamento' => "pago",
                    'code' => $code,
                    'descricao' => "REEMBOLSO DOS FACTURA ANUALADA",
                    'movimento' => "S",
                    'date_at' => date("Y-m-d"),
                    'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                    'code_caixa' => $caixaActivo ? $caixaActivo->code_caixa : NULL,
                    'status_caixa' => $caixaActivo ? 'pendente' : 'concluido',
                    'user_id' => Auth::user()->id,
                    'user_open_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                    'exercicio_id' => $request->exercicio_id ?? $this->exercicio(),
                    'periodo_id' => $request->periodo_id ?? $this->periodo(),
                ]);

                Registro::create([
                    "registro" => "Entrada de Stock",
                    "data_registro" => date('Y-m-d'),
                    "tipo" => "E",
                    'status' => 'D',
                    "quantidade" => $item['quantidade'],
                    "produto_id" => $item['produto_id'],
                    "observacao" => "devolução de produto - motivo: {$request->motivo}",
                    "loja_id" => $loja ? $loja->id : null,
                    "lote_id" => $item['lote_id'],
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                ItemDevolucao::create([
                    'devolucao_id' => $devolucao->id,
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
            }

            foreach ($factura->items as $value) {
                $update = ItemVenda::findOrFail($value->id);
                $produto = Produto::with('estoque')->findOrFail($update->produto_id);

                $totalValorItem += $update->total;
                $totalPagar += $update->valor_pagar;
                $lucro_total += $update->lucro;
                $custo_total += $update->custo;
                $totalValorBase += $value->valor_base;
                $totalValorIva += $value->valor_iva;
                $totalItems += $value->quantidade;
                $totalDesconto += $update->desconto_aplicado_valor;
                $totalRetencao += $update->retencao_fonte;
            }

            $datactual = Carbon::createFromFormat('Y-m-d H:i:s', $factura->data_documento);

            // HASH
            $hash = 'sha1'; // Tipo de Hash
            $rsa->setHash(strtolower($hash)); // Configurando para o tipo Hash especificado em cima

            $total_a_pagar = $totalValorIva +  $totalValorBase;

            $valor_extenso = $this->valor_por_extenso($total_a_pagar);

            $plaintext = $datactual->format('Y-m-d') . ';' . str_replace(' ', 'T', $datactual) . ';' . "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$factura->codigo_factura}" . ';' . number_format($total_a_pagar, 2, ".", "") . ';' . "";
            //ASSINATURA
            $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1); //Tipo de assinatura
            $signaturePlaintext = $rsa->sign($plaintext); //Assinando o texto $plaintext(resultado das concatenações)                

            $pagamento = $factura->pagamento == "NU";
            $valor_multicaixa = 0;
            $valor_cash = 0;
            if ($factura->pagamento == "NU") {
                $valor_cash = $total_a_pagar;
                $valor_multicaixa = 0;
            } else {
                $valor_cash = 0;
                $valor_multicaixa = $total_a_pagar;
            }

            // Para o primeiro registro, o update será normal
            $factura->update([
                'valor_entregue' => $total_a_pagar,
                'valor_total' => $total_a_pagar,
                'lucro_total' => $lucro_total,
                'custo_total' => $custo_total,
                'desconto' => $totalDesconto,
                'valor_extenso' => $valor_extenso,
                'valor_extenso' => $valor_extenso,
                'valor_cash' => $valor_cash,
                'valor_multicaixa' => $valor_multicaixa,

                'total_iva' => $totalValorIva,
                'total_incidencia' => $totalValorBase,
                'quantidade' => $totalItems,
                'data_documento' => $factura->data_documento,
                'codigo_factura' => $factura->codigo_factura,
                'ano_factura' => $entidade->empresa->ano_factura,
                'factura_next' => "{$request->tipo_documento} {$entidade->empresa->sigla_factura}{$entidade->empresa->ano_factura}/{$factura->codigo_factura}",
                'hash' => base64_encode($signaturePlaintext),
                'texto_hash' => $plaintext, // Exemplo de hash para o primeiro registro
            ]);


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['mensagem' => 'Devolução registrada com sucesso']);
    }
}
