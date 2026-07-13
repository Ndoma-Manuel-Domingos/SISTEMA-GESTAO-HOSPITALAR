<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Caixa;
use App\Models\CartaoConsumo;
use App\Models\CartaoConsumoHistorico;
use App\Models\CartaoConsumoMovimento;
use Illuminate\Support\Facades\Session;
use App\Models\Cliente;
use App\Models\Entidade;
use App\Models\ItemPedidoCuzinha;
use App\Models\ItemVenda;
use App\Models\Mesa;
use App\Models\OperacaoFinanceiro;
use App\Models\PedidoCuzinha;
use App\Models\Receita;
use App\Models\User;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CuzinhaController extends Controller
{

    use TraitHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function enviarPedidoCuzinha($id)
    {
        $user = auth()->user();

        if (!$user->can('controle cuzinha')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $datactual = Carbon::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));

            $mesa = Mesa::findOrFail($id);

            $movimentos = ItemVenda::where("user_id", Auth::user()->id)
                ->where("mesa_id", $mesa->id)
                ->where("status_uso", "MESA")
                ->where("status", "processo")
                ->where("entidade_id", $entidade->empresa->id)
                ->where("code", NULL)
                ->get();


            // GESTÃO DE PEDIDOS A CUZINHA
            $inicioDoDia = Carbon::parse($datactual)->startOfDay();
            $fimDoDia = Carbon::parse($datactual)->endOfDay();

            $total_pedidos = PedidoCuzinha::whereBetween("created_at", [$inicioDoDia, $fimDoDia])->where("entidade_id", $entidade->empresa->id)->count();
            $total_pedidos = $total_pedidos  + 1;

            $ped = PedidoCuzinha::where('status2', 'N')
                ->where('mesa_id', $mesa->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if (!$ped) {
                $pedido = PedidoCuzinha::create([
                    'numero' => $total_pedidos,
                    'status' => 'A preparar',
                    'factura_id' => NULL,
                    'mesa_id' => $mesa->id,
                    'status2' => "N",
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);

                if ($movimentos) {
                    foreach ($movimentos as $value) {
                        ItemPedidoCuzinha::create([
                            'pedido_id' => $pedido->id,
                            'produto_id' => $value->produto_id,
                            'quantidade' => $value->quantidade,
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id,
                        ]);
                    }
                }
            } else {
                if ($movimentos) {
                    foreach ($movimentos as $value) {

                        $ite = ItemPedidoCuzinha::where('pedido_id', $ped->id)
                            ->where('produto_id', $value->produto_id)
                            ->where('entidade_id', $entidade->empresa->id)
                            ->first();

                        if ($ite) {
                            $ite->quantidade = $value->quantidade;
                            $ite->save();
                        } else {
                            ItemPedidoCuzinha::create([
                                'pedido_id' => $ped->id,
                                'produto_id' => $value->produto_id,
                                'quantidade' => $value->quantidade,
                                'user_id' => Auth::user()->id,
                                'entidade_id' => $entidade->empresa->id,
                            ]);
                        }
                    }
                }
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e);
            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('controle cuzinha')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $pedidos = PedidoCuzinha::with(['items.produto'])
            ->where('entidade_id', $entidade->entidade_id)
            ->orderBy('id', 'desc')
            ->get();

        $head = [
            "titulo" => "Cuzinha",
            "descricao" => env('APP_NAME'),
            "pedidos" => $pedidos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cuzinhas.index', $head);
    }

    // Atualizar o status de um pedido
    public function updateStatus(Request $request, PedidoCuzinha $pedido)
    {
        $status = "";
        $agora = now();

        if ($pedido->status == "A preparar") {
            $status = "Em preparo";
            $pedido->data_em_preparo = $agora;
        } else  if ($pedido->status == "Em preparo") {
            $status = "Pronto";
            $pedido->data_pronto = $agora;
        } else if ($pedido->status == "Pronto") {
            $status = "Entregue";
            $pedido->data_entregue = $agora;
        } else if ($pedido->status == "Entregue") {
            $status = "A preparar";
            $pedido->data_a_preparar = $agora;
        }

        $pedido->status = $status;
        $pedido->save();

        return response()->json(['msg' => 'Status atualizado!', 'pedido' => $pedido]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            CartaoConsumo::create([
                'nome' => $request->nome,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function carregarSaldo(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartao = CartaoConsumo::findOrFail($request->cartao);
            $cartao->saldo += $request->valor;
            $cartao->status = "Y";
            $cartao->save();

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $cartao_carregado = CartaoConsumoHistorico::create([
                "tipo" => "D",
                "saldo" => $request->valor,
                "date_at" => now(),
                "cartao_id" => $cartao->id,
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
            ]);

            CartaoConsumoMovimento::create([
                "cartao_id" => $cartao->id,
                "saldo" => $request->valor,
                "descricao" => "carregamento de do cartão de consumo",
                "date_at" => now(),
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", 'cartao_carregado' => $cartao_carregado], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function gerar_comprovativo($id)
    {
        $cartao = CartaoConsumoHistorico::with(['cartao'])->findOrFail($id);

        $head = [
            "titulo" => "COMPROVATIVO DE CARREGAMENTO DE SALDO",
            "descricao" => env("APP_NAME"),
            "cartao" => $cartao,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.facturas.documentos.comprovativo", $head);

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!", 'cartao' => $cartao], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartao = CartaoConsumo::findOrFail($request->cartao);
            $cartao->nome = $request->designacao;
            $cartao->saldo = $request->saldo;
            $cartao->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function movimentos($id)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function recuperar_saldos()
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $cartoes = CartaoConsumo::where("status", "Y")->where('entidade_id', $entidade->entidade_id)->get();

            $saldo = 0;
            if ($cartoes) {
                foreach ($cartoes as $item) {

                    $saldo += $item->saldo;

                    $cartao = CartaoConsumo::findOrFail($item->id);
                    $cartao->status = "N";
                    $cartao->saldo = 0;
                    $cartao->save();
                }
            }

            $caixaActivo = Caixa::where("active", true)
                ->where("status", "aberto")
                ->where('status_admin', 'liberado')
                ->where("entidade_id", $entidade->empresa->id)
                ->first();

            if (!$caixaActivo) {
                $caixaActivo = Caixa::where("entidade_id", $entidade->empresa->id)
                    ->where('status_admin', 'liberado')->first();
            }
            $receita = Receita::where("type", "R")->where("entidade_id", $entidade->empresa->id)->first();
            $code = uniqid(time());
            $cliente = Cliente::where("entidade_id", $entidade->empresa->id)->first();

            OperacaoFinanceiro::create([
                "nome" => "recuperção de saldo dos cartões",
                "status" => "pago",
                "formas" => "C",
                "motante" => $saldo,
                "subconta_id" => $caixaActivo->subconta_id,
                "cliente_id" => $cliente->id,
                "model_id" => $receita ? $receita->id : NULL,
                "type" => "R",
                "parcelado" => "N",
                "status_pagamento" => "pago",
                "data_recebimento" => date("Y-m-d"),
                "forma_recebimento_id" => 1,
                "code" => $code,
                'loja_id' => $this->LOJA_ACTIVA_USER() ? $this->LOJA_ACTIVA_USER()->id  : NULL,
                "descricao" => "recuperção de saldo dos cartões",
                "movimento" => "E",
                "date_at" => date("Y-m-d"),
                "user_id" => Auth::user()->id,
                "entidade_id" => $entidade->empresa->id,
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

        return response()->json(['success' => true, 'message' => "operação realizada com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function definir_tipo_venda(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            $empresa = Entidade::findOrFail($entidade->empresa->id);

            $status = "";
            if ($empresa->tipo_venda == "Normal") {
                $status = "Cartao Consumo";
            }

            if ($empresa->tipo_venda == "Cartao Consumo") {
                $status = "Normal";
            }

            $empresa->tipo_venda = $status;
            $empresa->save();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "operação realizada com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validar_pin(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'pin' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            Session::forget("carta_consumo_venda_2022");

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $cartao = CartaoConsumo::where('nome', $request->pin)->where('entidade_id', $entidade->empresa->id)->first();

            if (!$cartao) {
                return response()->json(['erro' => 'Cartão não encontrado.'], 404);
            }

            if ($cartao->saldo <= 0) {
                return response()->json(['success' => true, 'message' => "Cartão indisponíve, são insuficiente!"], 404);
            }
            // Exemplo: guardar apenas o PIN ou ID do cliente (melhor opção)
            Session::put('carta_consumo_venda_2022', $cartao);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "operação realizada com sucesso!"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function historico($id)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $cartao = CartaoConsumo::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $historicos = CartaoConsumoHistorico::where("cartao_id", $cartao->id)->where("entidade_id", $entidade->empresa->id)->get();

        // Aqui você pode usar um relacionamento para buscar os históricos reais
        return response()->json($historicos);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        if (!$user->can('listar todos') && !$user->can('listar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $cartao = CartaoConsumo::with(["historicos", "movimentos"])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "cartao" => $cartao,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.cartoes_consumos.show', $head);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $cartao = CartaoConsumo::findOrFail($id);

        return response()->json(['success' => true, 'data' => $cartao], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        if (!$user->can('editar todos') && !$user->can('editar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartao = CartaoConsumo::findOrFail($id);
            $cartao->update($request->all());

            $cartao->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar cartao')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cartao = CartaoConsumo::findOrFail($id);
            $cartao->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }
}
