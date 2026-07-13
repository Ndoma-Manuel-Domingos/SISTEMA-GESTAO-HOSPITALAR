<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Fornecedore;
use App\Models\LojaProduto;
use App\Models\OperacaoFinanceiro;
use App\Models\Pin;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $pins = Pin::where("entidade_id", $entidade->empresa->id)->get();

        if (empty($pins)) {
            return redirect()->route("pins.create");
        }

        // recuperar todos os caixas aberto
        $caixas = Caixa::where("active", false)->where("status", "fechado")
            ->where('status_admin', 'liberado')->where("entidade_id", $entidade->empresa->id)->get();

        if (empty($caixas)) {
            return redirect()->route("caixa.caixas");
        }


        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();


        $receitas = Receita::where('type', 'R')->where('entidade_id', $entidade->empresa->id)->get();
        $dispesas = Receita::where('type', 'D')->where('entidade_id', $entidade->empresa->id)->get();
        $fornecedores = Fornecedore::where('entidade_id', '=', $entidade->empresa->id)->get();

        $data_actual = date("Y-m-d");

        $operacoes = OperacaoFinanceiro::where('date_at', $data_actual)
            ->whereIn('status_caixa', ['pendente'])
            ->where('entidade_id', $entidade->empresa->id)
            ->where('user_open_id', Auth::user()->id)
            ->whereIn('type', ['R', 'D'])
            ->with(['centro_custo', 'fornecedor', 'cliente', 'dispesa', 'caixa', 'contabancaria', 'receita', 'subconta'])
            ->orderBy('created_at', 'desc')
            ->get();



        $head = [
            "clientes" => Cliente::where("entidade_id", $entidade->empresa->id)->get(),
            "forma_pagmento" => TipoPagamento::get(),
            "caixas" => $caixas,

            "receitas" => $receitas,
            "dispesas" => $dispesas,
            "fornecedores" => $fornecedores,
            "operacoes" => $operacoes,

            "checkCaixa" => $checkCaixa,

            "titulo" => "Pronto Vendas",
            "descricao" => env("APP_NAME"),

            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),

        ];

        return view('dashboard.vendas.index-grelha', $head);
    }

    public function produtos(Request $request)
    {

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::when($request->q, function ($query, $q) {
            $query->where(function ($q2) use ($q) {
                $q2->where('nome', 'like', "%" . $q . "%")
                    ->orWhere('codigo_barra', 'like', "%" . $q . "%");
            });
        })
            ->whereIn("tipo", ["P", "S"])
            ->whereIn("id", $meus_produtos)
            ->where("entidade_id", $entidade->empresa->id)
            ->get();

        $produtos = $produtos->map(function ($p) {
            return [
                'id' => $p->id,
                'nome' => $p->nome,
                'codigo_barra' => $p->codigo_barra,
                'preco_custo' => $p->preco_custo,
                'preco_venda' => $p->preco_venda,
                'taxa' => $p->taxa,
                'total_produto_loja_activa' => $p->total_produto_loja_activa(),
            ];
        });


        return response()->json($produtos);
    }

    public function store(Request $request)
    {

        dd($request->all());

        // DB::transaction(function () use ($request) {
        //     $venda = Sale::create([
        //         'tipo_documento' => $request->tipo_documento,
        //         'forma_pagamento' => $request->forma_pagamento,
        //         'valor_entregue' => $request->valor_entregue,
        //         'total' => $request->total,
        //         'troco' => $request->troco,
        //     ]);

        //     foreach ($request->items as $item) {
        //         SaleItem::create([
        //             'sale_id' => $venda->id,
        //             'product_id' => $item['id'],
        //             'preco' => $item['preco'],
        //             'quantidade' => $item['quantidade'],
        //             'subtotal' => $item['subtotal'],
        //         ]);
        //     }
        // });

        return response()->json(['success' => true]);
    }
}
