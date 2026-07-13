<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\ContaCliente;
use App\Models\Entidade;
use App\Models\Loja;
use App\Models\MovimentoContaCliente;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ContaClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $movimentos = ContaCliente::where('entidade_id', '=', $entidade->empresa->id)
            ->with(['cliente'])
            ->get();

        $hoje = Carbon::now()->toDateString(); // Data atual no formato YYYY-MM-DD

        // Faturas vencidas
        $facturasVencidas = Venda::whereDate('data_vencimento', '<', $hoje)
            ->where('status_factura', 'por pagar')
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_total');

        // Dívidas correntes
        $facturasVencidasCorrente = Venda::where('status_factura', 'por pagar')
            ->where('entidade_id', $entidade->empresa->id)
            ->whereDate('data_emissao', '<=', $hoje)
            ->whereDate('data_vencimento', '>=', $hoje)
            ->sum('valor_total');


        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        $head = [
            "titulo" => "Regularização da conta corrente - cliente",
            "descricao" => env('APP_NAME'),
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "empresa" => $empresa,
            "movimentos" => $movimentos,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Regularização da conta corrente - cliente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.create', $head);
    }

    public function movimentosConta($id)
    {
        $cliente = Cliente::findOrFail($id);
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $conta = ContaCliente::where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();

        $hoje = Carbon::now()->toDateString(); // Data atual no formato YYYY-MM-DD

        $facturas = Venda::where("status_factura", "por pagar")
            ->where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->with(["cliente"])
            ->orderby("created_at", "desc")
            ->get();

        $valorTotalCompras = Venda::where("status_factura", "pago")
            ->where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->sum("valor_total");

        // Faturas vencidas
        $facturasVencidas = Venda::whereDate("data_vencimento", "<=", $hoje)
            ->where("status_factura", "por pagar")
            ->where("entidade_id", $entidade->empresa->id)
            ->where("cliente_id", $cliente->id)
            ->sum("valor_total");

        // Dívidas correntes
        $facturasVencidasCorrente = Venda::where("status_factura", "por pagar")
            ->where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->whereDate("data_vencimento", ">", $hoje)
            ->sum("valor_total");

        $movimentos = MovimentoContaCliente::where("cliente_id", $cliente->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $empresa = User::with("empresa")->findOrFail(Auth::user()->id);

        $head = [
            "titulo" => "Regularização da conta corrente - cliente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "cliente" => $cliente,
            "movimentos" => $movimentos,
            "facturas" => $facturas,
            "valorTotalCompras" => $valorTotalCompras,
            "facturasVencidas" => $facturasVencidas,
            "facturasVencidasCorrente" => $facturasVencidasCorrente,
            "conta" => $conta,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.movimentos', $head);
    }

    public function actualizarConta($id)
    {
        $cliente = Cliente::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "clienteSaldo" => $cliente,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.create', $head);
    }


    public function liquidarfactura($id)
    {
        $cliente = Cliente::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $conta = ContaCliente::where('entidade_id', '=', $entidade->empresa->id)
            ->where('cliente_id', '=', $cliente->id)
            ->first();

        $facturas = Venda::where('status_factura', '=', 'por pagar')
            ->where('cliente_id', '=', $cliente->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => "Regularização Conta Corrente",
            "descricao" => env('APP_NAME'),
            "loja" => $empresa,
            "cliente" => $cliente,
            "facturas" => $facturas,
            "conta" => $conta,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.facturas', $head);
    }

    public function extratoConta(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $facturas = Venda::where('cliente_id', '=', $cliente->id)
            ->where('entidade_id', '=', $entidade->empresa->id)
            ->with('cliente')
            ->orderby('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();

        $head = [
            "titulo" => "Extrato de conta do Cliente",
            "descricao" => env('APP_NAME'),
            "loja" => $empresa,
            "cliente" => $cliente,
            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "lojas" => $lojas,
            'requests' => $request->all('data_inicio', 'data_final', 'loja_id', 'tipo_documento'),
            "facturas" => $facturas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.clientes.contas.extrato', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "observacao" => "required",
            "montante" => "required",
            "tipo_movimento" => "required",
        ]);

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $conta = ContaCliente::where("entidade_id", $entidade->empresa->id)
                ->where("cliente_id", $request->cliente_id)
                ->first();

            if ($conta) {

                $cartao = ContaCliente::where('cliente_id', $request->cliente_id)->firstOrFail();

                MovimentoContaCliente::create([
                    "user_id" => Auth::user()->id,
                    "conta_id" => $cartao->id,
                    "observacao" => $request->observacao,
                    "montante" => $request->montante,
                    "cliente_id" => $request->cliente_id,
                    "data_emissao" => date("Y-m-d"),
                    "tipo_movimento" => $request->tipo_movimento,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                if ($request->tipo_movimento == "-1") {
                    $cartao->saldo -= $request->montante;
                    $cartao->save();
                } else {
                    $cartao->saldo += $request->montante;
                    $cartao->save();
                }
            } else {

                if ($request->tipo_movimento == "-1") {
                    $saldo = (0 - $request->montante);
                } else {
                    $saldo = (0 + $request->montante);
                }

                $cartao = ContaCliente::create([
                    "user_id" => Auth::user()->id,
                    "divida_corrente" => 0,
                    "divida_vencida" => 0,
                    "saldo" => $saldo,
                    "cliente_id" => $request->cliente_id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                MovimentoContaCliente::create([
                    "user_id" => Auth::user()->id,
                    "observacao" => $request->observacao,
                    "montante" => $request->montante,
                    "conta_id" => $cartao->id,
                    "cliente_id" => $request->cliente_id,
                    "data_emissao" => date("Y-m-d"),
                    "tipo_movimento" => $request->tipo_movimento,
                    "entidade_id" => $entidade->empresa->id,
                ]);
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning("Informação", $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["success" => true, "message" => "Dados Salvos com sucesso!", "url" => route("clientes-movimentos-conta", $request->cliente_id)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
