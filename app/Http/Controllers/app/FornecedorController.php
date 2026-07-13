<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Imports\FornecedorImport;
use App\Models\Conta;
use App\Models\ContaFornecedore;
use App\Models\EncomendaFornecedore;
use App\Models\Entidade;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\Fornecedore;
use App\Models\ItensEncomenda;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Produto;
use App\Models\Subconta;
use App\Models\User;
use App\Models\UserLoja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class FornecedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fornecedores = Fornecedore::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $empresa = User::with("variacoes")->with(['empresa'])->with('clientes')->with("categorias")->with("marcas")->findOrFail(Auth::user()->id);

        $facturaAtrso = FacturaEncomendaFornecedor::where([
            ['data_vencimento', '<', date('Y-m-d')],
            ['status2', '=', 'nao concluido'],
            ['status', false],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->count();

        $dividaVencida = FacturaEncomendaFornecedor::where([
            ['data_vencimento', '<', date('Y-m-d')],
            ['status2', '=', 'nao concluido'],
            ['status', false],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->sum('valor_factura');

        $dividaCorrente = FacturaEncomendaFornecedor::where([
            ['status2', '=', 'nao concluido'],
            ['data_factura', '<=', date("Y-m-d")],
            ['data_vencimento', '>=', date("Y-m-d")],
            ['status', false],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->sum('valor_factura');

        $saldo = $dividaCorrente + $dividaVencida;

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "fornecedores" => $fornecedores,
            "empresa" => $empresa,
            "facturaAtrso" => $facturaAtrso,
            "saldo" => $saldo,
            "dividaVencida" => $dividaVencida,
            "dividaCorrente" => $dividaCorrente,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.index', $head);
    }

    public function create_import()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.importar_excel'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.create-import', $head);
    }

    public function store_import(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        try {
            Excel::import(new FornecedorImport, $request->file('file'));
            return redirect()->back()->with('success', 'Dados importados com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao importar dados: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao importar dados: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "user" => Auth::user(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $code = uniqid(time());
            $nova_conta = "";

            if ($request->tipo_fornecedor == "corrente") {
                $conta = Conta::where('conta', '32')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->pais == "AO") {
                    $serie =  "32.1.2.1.";
                } else {
                    $serie =  "32.1.2.2.";
                }
            }
            if ($request->tipo_fornecedor == "titulos a pagar") {
                $conta = Conta::where('conta', '32')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->pais == "AO") {
                    $serie =  "32.2.2.1.";
                } else {
                    $serie =  "32.2.2.2.";
                }
            }
            if ($request->tipo_fornecedor == "imobilizado") {
                $conta = Conta::where('conta', '37')->where('entidade_id', $entidade->empresa->id)->first();
                $serie =  "37.1.1.";
            }

            $subc_ = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                'entidade_id' => $entidade->empresa->id,
                'numero' => $nova_conta,
                'nome' => $request->nome,
                'tipo_conta' => 'M',
                'code' => $code,
                'status' => $conta->status,
                'conta_id' => $conta->id,
                'user_id' => Auth::user()->id,
            ]);

            $fornecedores = Fornecedore::create([
                "nif" => $request->nif,
                "code" => $code,
                "nome" => $request->nome,
                'conta' => $nova_conta,
                "pais" => $request->pais,
                "status" => true,
                "codigo_postal" => $request->codigo_postal,
                "localidade" => $request->localidade,
                "telefone" => $request->telefone,
                "telemovel" => $request->telemovel,
                "email" => $request->email,
                "website" => $request->website,
                "tipo_pessoa" => $request->tipo_pessoa,
                "tipo_fornecedor" => $request->tipo_fornecedor,
                "observacao" => $request->observacao,
                "user_id" => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
                'subconta_id' => $subconta->id,
            ]);

            $saldo = ContaFornecedore::create([
                'user_id' => Auth::user()->id,
                'divida_corrente' => 0,
                'divida_vencida' => 0,
                'saldo' => 0,
                'fornecedor_id' => $fornecedores->id,
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

        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('listar todos') && !$user->can('listar fornecedores')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fornecedor = Fornecedore::findOrFail($id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $conta = ContaFornecedore::where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->first();

        $encomendasPendetes = EncomendaFornecedore::whereIn('status', ['pendente'])
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $encomendascanceladas = EncomendaFornecedore::whereIn('status', ['cancelada'])
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $encomendasEntregues = EncomendaFornecedore::whereIn('status', ['entregue'])
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->get();


        $facturaAtrso = FacturaEncomendaFornecedor::whereDate('data_vencimento', '<', date('Y-m-d'))
            ->whereIn('status2', ['nao concluido'])
            ->where('status', false)
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $dividasVencidas = FacturaEncomendaFornecedor::whereDate('data_vencimento', '<', date('Y-m-d'))
            ->whereIn('status2', ['nao concluido'])
            ->where('status', false)
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_factura');


        $dividasCorrente = FacturaEncomendaFornecedor::whereDate('data_factura', '<=', date("Y-m-d"))
            ->whereDate('data_vencimento', '>=', date("Y-m-d"))
            ->whereIn('status2', ['nao concluido'])
            ->where('status', false)
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->sum('valor_factura');

        $totalEncomendas = EncomendaFornecedore::where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $totalFacturas = FacturaEncomendaFornecedor::where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->count();

        $facturasPagas = FacturaEncomendaFornecedor::whereIn('status2', ['concluido'])
            ->where('status', true)
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['fornecedor', 'user', 'encomenda'])->orderBy('created_at', 'desc')->get();

        $facturasNaoPagas = FacturaEncomendaFornecedor::whereIn('status2', ['nao concluido'])
            ->where('status', false)
            ->where('fornecedor_id', $fornecedor->id)
            ->where('entidade_id', $entidade->empresa->id)
            ->with(['fornecedor', 'user', 'encomenda'])->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Fornecedor",
            "descricao" => env('APP_NAME'),
            "conta" => $conta,
            "empresa" => $empresa,
            "fornecedor" => $fornecedor,
            "facturaAtraso" => $facturaAtrso,
            "dividasVencidas" => $dividasVencidas,
            "dividasCorrente" => $dividasCorrente,
            "encomendasPendetes" => $encomendasPendetes,
            "encomendascanceladas" => $encomendascanceladas,
            "encomendasEntregues" => $encomendasEntregues,

            "facturasPagas" => $facturasPagas,
            "facturasNaoPagas" => $facturasNaoPagas,

            "totalFacturas" => $totalFacturas,
            "totalEncomendas" => $totalEncomendas,

            "loja" => User::with(['empresa'])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $fornecedores = Fornecedore::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "fornecedor" => $fornecedores,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $request->validate([
            'nome' => 'required|string',
            'nif' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $fornecedores = Fornecedore::findOrFail($id);

            if ($request->tipo_fornecedor == "corrente") {
                $conta = Conta::where('conta', '32')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->pais == "AO") {
                    $serie =  "32.1.2.1.";
                } else {
                    $serie =  "32.1.2.2.";
                }
            }
            if ($request->tipo_fornecedor == "titulos a pagar") {
                $conta = Conta::where('conta', '32')->where('entidade_id', $entidade->empresa->id)->first();
                if ($request->pais == "AO") {
                    $serie =  "32.2.2.1.";
                } else {
                    $serie =  "32.2.2.2.";
                }
            }
            if ($request->tipo_fornecedor == "imobilizados") {
                $conta = Conta::where('conta', '37')->where('entidade_id', $entidade->empresa->id)->first();
                $serie =  "37.1.1.";
            }

            if ($request->tipo_fornecedor == $fornecedores->tipo_fornecedor && $fornecedores->pais == $request->pais) {
                $fornecedores->nif = $request->nif;
                $fornecedores->nome = $request->nome;
                $fornecedores->pais = $request->pais;
                $fornecedores->codigo_postal = $request->codigo_postal;
                $fornecedores->localidade = $request->localidade;
                $fornecedores->telefone = $request->telefone;
                $fornecedores->telemovel = $request->telemovel;
                $fornecedores->email = $request->email;
                $fornecedores->website = $request->website;
                $fornecedores->tipo_fornecedor = $request->tipo_fornecedor;
                $fornecedores->tipo_pessoa = $request->tipo_pessoa;
                $fornecedores->observacao = $request->observacao;
                $fornecedores->update();
            } else {
                $subc_ = Subconta::where('id', $fornecedores->subconta_id)->where('entidade_id', $entidade->empresa->id)->first();

                if ($subc_) {
                    $numero = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                    $nova_conta =  $serie . "{$numero}";

                    $subconta = Subconta::findOrFail($fornecedores->subconta_id);
                    $subconta->numero = $nova_conta;
                    $subconta->nome = $request->nome;
                    $subconta->update();

                    $code =  $subconta->code;
                } else {
                    $numero = Subconta::where('numero', 'like', "{$serie}%")->where('entidade_id', $entidade->empresa->id)->count() + 1;
                    $nova_conta =  $serie . "{$numero}";
                    $code = uniqid(time());

                    $subconta = Subconta::create([
                        'entidade_id' => $entidade->empresa->id,
                        'numero' => $nova_conta,
                        'nome' => $request->nome,
                        'tipo_conta' => 'M',
                        'code' => $code,
                        'status' => $conta->status,
                        'conta_id' => $conta->id,
                        'user_id' => Auth::user()->id,
                    ]);

                    $fornecedores->subconta_id = $subconta->id;
                }

                $fornecedores->nif = $request->nif;
                $fornecedores->code = $code;
                $fornecedores->nome = $request->nome;
                $fornecedores->conta = $nova_conta;
                $fornecedores->pais = $request->pais;
                $fornecedores->codigo_postal = $request->codigo_postal;
                $fornecedores->localidade = $request->localidade;
                $fornecedores->telefone = $request->telefone;
                $fornecedores->telemovel = $request->telemovel;
                $fornecedores->email = $request->email;
                $fornecedores->website = $request->website;
                $fornecedores->tipo_fornecedor = $request->tipo_fornecedor;
                $fornecedores->tipo_pessoa = $request->tipo_pessoa;
                $fornecedores->subconta_id = $subconta->id;
                $fornecedores->observacao = $request->observacao;
                $fornecedores->update();
            }
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'message' => "Dados salvos com sucesso!"], 200);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar fornecedores')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $fornecedores = Fornecedore::findOrFail($id);
            $fornecedores->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados excluídos com sucesso!"], 200);
    }

    public function novaEncomanda($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar encomendas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $fornecedores = Fornecedore::findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);
        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $fornecedores->id],
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 'em processo'],
            ['code', '=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto')
            ->get();


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");

        $produtos = Produto::whereIn("id", $meus_produtos)->where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->get();

        $totalEncomendas = EncomendaFornecedore::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '!=', 'em processo'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->count();

        $resultado = $totalEncomendas + 1;

        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "titulo" => "Adicionar Encomenda",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "produtos" => $produtos,
            "fornecedor" => $fornecedores,
            "items" => $items,
            "lojas" => $lojas,
            "totalEncomendas" =>  $resultado . "-" . date('y') . "" . date('m') . "" . date('d') . "/F",
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.contas.nova-encomenda', $head);
    }

    public function itemsNovaEncomanda($id, $for)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar encomendas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $fornecedor = Fornecedore::findOrFail($for);
        $produto = Produto::findOrFail($id);

        $verificar = ItensEncomenda::where([
            'fornecedor_id' => $fornecedor->id,
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ])->first();

        if ($verificar) {
            Alert::error("Erro", "Este produto Já foi Adicionar... Pode alterar a quantidade");
            return redirect()->route('fornecedores-nova-encomenda', $fornecedor->id);
        }

        $iva = "";

        if ($produto->imposto == "ISE") {
            $iva = 0;
        } else if ($produto->imposto == "RED") {
            $iva = 2;
        } else if ($produto->imposto == "INT") {
            $iva = 5;
        } else if ($produto->imposto == "OUT") {
            $iva = 7;
        } else if ($produto->imposto == "NOR") {
            $iva = 14;
        } else {
            $iva = 0;
        }

        $items = ItensEncomenda::create([
            'fornecedor_id' => $fornecedor->id,
            'produto_id' => $produto->id,
            'user_id' => Auth::user()->id,
            'quantidade' => 1,
            'desconto' => 0,
            'data_emissao' => date('Y-m-d'),
            'status' => 'em processo',
            'custo' => $produto->preco_custo,
            'iva' => $iva,
            'total' => $produto->preco_custo * 1,
            'code' =>  NULL,
            'entidade_id' => $entidade->empresa->id,
        ]);

        if ($items->save()) {
            return redirect()->route('fornecedores-nova-encomenda', $fornecedor->id);
        } else {
            Alert::error("Erro", "Ocorreu um erro ao tentar adicionar este produto");
            return redirect()->route('fornecedores-nova-encomenda', $fornecedor->id);
        }
    }

    public function itemsNovaEncomandaActualizar($id, $forn)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar encomendas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $fornecedor = Fornecedore::findOrFail($forn);
        $encomenda = ItensEncomenda::findOrFail($id);
    }

    public function itemsNovaEncomandaRemover($id, $forn)
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar encomendas')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $fornecedor = Fornecedore::findOrFail($forn);
        $encomenda = ItensEncomenda::findOrFail($id);

        if ($fornecedor) {
            if ($encomenda->delete()) {
                return redirect()->route('fornecedores-nova-encomenda', $id);
            }
        }
    }

    public function novaFactura($id)
    {
        $fornecedores = Fornecedore::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $empresa = Entidade::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "fornecedor" => $fornecedores,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.edit', $head);
    }

    public function movimentos($id)
    {
        $fornecedores = Fornecedore::findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $empresa = User::with(["variacoes", "categorias", "marcas"])->findOrFail($entidade->id);

        $totalEncomendas = EncomendaFornecedore::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 'realizado'],
            ['code', '!=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])->count();

        $resultado = $totalEncomendas + 1;


        $minhas_lojas = UserLoja::where("usuario_id", Auth::user()->id)->pluck("loja_id");
        $meus_produtos = LojaProduto::whereIn("loja_id", $minhas_lojas)->pluck("produto_id");


        $produtos = Produto::whereIn("id", $meus_produtos)->where([
            ['status', '=', 'activo'],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->get();

        $items = ItensEncomenda::where([
            ['fornecedor_id', '=', $fornecedores->id],
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 'em processo'],
            ['code', '=', NULL],
            ['entidade_id', '=', $entidade->empresa->id],
        ])
            ->with('produto')
            ->get();



        // $verifica se tem uma loja activa onde esta sendo retidados os produtos
        $lojas = Loja::where("entidade_id", $entidade->empresa->id)
            ->whereIn("id", $minhas_lojas)
            ->get();


        $head = [
            "items" => $items,
            "produtos" => $produtos,
            "titulo" => __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "loja" => $empresa,
            "lojas" => $lojas,
            "fornecedor" => $fornecedores,
            "totalEncomendas" =>  $resultado . "-" . date('y') . "" . date('m') . "" . date('d') . "/F",
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.fornecedores.contas.nova-encomenda', $head);
    }
}
