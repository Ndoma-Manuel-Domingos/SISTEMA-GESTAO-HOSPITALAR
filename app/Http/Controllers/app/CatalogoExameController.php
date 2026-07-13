<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\CatalogoExame\ExameCatalogo;
use App\Models\Categoria;
use App\Models\Conta;
use App\Models\Estoque;
use App\Models\Imposto;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Marca;
use App\Models\Motivo;
use App\Models\ParamentroExame;
use App\Models\Produto;
use App\Models\Subconta;
use App\Models\SubParamentroExame;
use App\Models\User;
use App\Models\Variacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CatalogoExameController extends Controller
{

    use TraitHelpers;

    private $numero_aleatorio = [];

    public function __construct()
    {
        $this->numero_aleatorio = rand(10000, 99999);
        $this->middleware('auth');
    }

    public function getNumeros()
    {
        return $this->numero_aleatorio;
    }

    public function index(Request $request)
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar atendimento') && !$user->can('monitoramento central atendimento')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $exames = ExameCatalogo::orderBy("categoria", "asc")->get();

        $head = [
            "titulo" => "Catalogo Exames",
            "descricao" => env("APP_NAME"),
            "entidade" => $entidade,
            "exames" => $exames,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.catalogo_exames.index", $head);
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

        if (!$user->can('criar todos') && !$user->can('criar atendimento') && !$user->can('monitoramento central atendimento')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'exames' => 'required|array'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            foreach ($request->exames as $exame_id) {

                $exame = ExameCatalogo::with(['parametros.subparametros'])->findOrFail($exame_id);

                $conta = Conta::where('conta', '62')->where('entidade_id', $entidade->empresa->id)->first();
                $serie = "62.1.1";

                $subc_ = Subconta::where('numero', 'like', $serie . "%")->where('entidade_id', $entidade->empresa->id)->count();
                $numero =  $subc_ + 1;

                $nova_conta = $serie . "." . $numero;

                $code = uniqid(time());

                $subconta = Subconta::create([
                    'numero' => $nova_conta,
                    'nome' => $exame->nome,
                    'tipo_conta' => 'M',
                    'code' => $code,
                    'status' => $conta->status,
                    'conta_id' => $conta->id,
                    'entidade_id' => $entidade->empresa->id,
                    'user_id' => Auth::user()->id,
                ]);

                $motivo = Motivo::findOrFail($entidade->empresa->motivo_id ?? 1);
                $imposto = Imposto::findOrFail($entidade->empresa->imposto_id ?? 1);

                $categoria = Categoria::whereIn('categoria', ['Exames', 'exames', 'exame', 'Exame'])->where('entidade_id', $entidade->empresa->id)->first();

                $marca = Marca::updateOrCreate(
                    [
                        'entidade_id' => $entidade->empresa->id,
                        'nome' => '-- Sem Marca --',
                    ],
                    [
                        'nome' => '-- Sem Marca --',
                        "user_id" => Auth::user()->id,
                    ]
                );

                $variacao = Variacao::updateOrCreate(
                    [
                        'entidade_id' => $entidade->empresa->id,
                        'nome' => '-- Sem Variação --',
                    ],
                    [
                        'nome' => '-- Sem Variação --',
                        "user_id" => Auth::user()->id,
                    ]
                );

                $produto = Produto::create([
                    "nome" => $exame->nome,
                    "codigo_barra" => $code,
                    "conta" => $nova_conta,
                    "code" => $code,
                    "descricao" => $exame->nome,
                    "categoria_id" => $categoria->id,
                    "marca_id" => $marca->id ?? NULL,
                    "variacao_id" => $variacao->id ?? NULL,
                    "imposto_id" => $imposto->id,
                    "tipo" => "S",
                    "peso" => 0,
                    "unidade_id" => 6,
                    "imposto" => $imposto->codigo,
                    "taxa" => $imposto->valor,
                    "motivo_isencao" => $motivo->codigo,
                    "motivo_id" => $motivo->id,
                    "preco_custo" => 0,
                    "preco" => 0,
                    "margem" => 0,
                    "preco_venda" => 0,
                    "preco_venda_com_iva" => 0,
                    "controlo_stock" => "Sim",
                    "tipo_stock" => "M",
                    "disponibilidade" => 1,
                    "status" => "activo",
                    "subconta_id" => $subconta->id ?? 1,
                    "user_id" => Auth::user()->id,
                    "entidade_id" =>  $entidade->empresa->id,
                ]);

                foreach ($exame->parametros as $parametro) {

                    $proximaOrdem = ParamentroExame::where('exame_id', $produto->id)
                        ->max('ordem');

                    $proximaOrdem = $proximaOrdem ? $proximaOrdem + 1 : 1;

                    $aramet = ParamentroExame::create([
                        'exame_id' => $produto->id,
                        'nome' => $parametro->nome,
                        'ordem' => $proximaOrdem,
                        'observacao' => $parametro->nome,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id
                    ]);

                    foreach ($parametro->subparametros as $sub) {

                        $proximaOrdem = SubParamentroExame::where('parametro_id', $aramet->id)
                            ->max('ordem');

                        $proximaOrdem = $proximaOrdem ? $proximaOrdem + 1 : 1;

                        SubParamentroExame::create([
                            'exame_id' => $produto->id,
                            'parametro_id' => $aramet->id,
                            'nome' => $sub->nome,
                            // 'codigo' => $request->codigo,
                            'tipo' => $sub->tipo,
                            'unidade' => $sub->unidade,
                            'valor_referencia' => $sub->valor_referencia,
                            'valor_minimo' => $sub->valor_minimo,
                            'valor_maximo' => $sub->valor_maximo,
                            'texto_sim' => $sub->texto_sim,
                            'texto_nao' => $sub->texto_nao,
                            'opcoes' => $sub->opcoes,
                            'ordem' => $proximaOrdem,
                            'tamanho_maximo' => $sub->tamanho_maximo,
                            'valor_padrao' => 0,
                            'permitir_passado' => $sub->permitir_passado ?? 0,
                            'permitir_futuro' => $sub->permitir_futuro ?? 0,
                            'linhas' => $sub->linhas ?? NULL,
                            'extensoes_permitidas' => $sub->extensoes_permitidas,
                            'tamanho_max_arquivo' => "10M",
                            'obrigatorio' => 0,
                            'activo' => 1,
                            'user_id' => Auth::user()->id,
                            'entidade_id' => $entidade->empresa->id
                        ]);
                    }
                }

                $lojas = Loja::where('entidade_id', $entidade->empresa->id)->get();

                foreach ($lojas as $loja) {
                    LojaProduto::create([
                        'produto_id' => $produto->id,
                        'loja_id' => $loja->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                    Estoque::create([
                        "loja_id" => $loja->id,
                        "lote_id" => NULL,
                        "produto_id" => $produto->id,
                        "user_id" => Auth::user()->id,
                        "data_operacao" => date('Y-m-d'),
                        "stock" =>  0,
                        "observacao" => 'Entrada inicial de produtos de Stock',
                        "stock_minimo" => 5, // quantidade minima,
                        "operacao" => "Actualizar de Stock",
                        "entidade_id" => $entidade->empresa->id,
                    ]);
                }
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }
}
