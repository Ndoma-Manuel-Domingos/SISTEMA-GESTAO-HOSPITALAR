<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Agendamento;
use App\Models\Aluno;
use App\Models\AlunoConteudo;
use App\Models\Andar;
use App\Models\AnoLectivo;
use App\Models\Anuncio;
use App\Models\AnuncioAdmin;
use App\Models\AnuncioProva;
use App\Models\AnuncioUser;
use App\Models\ArquivoPasta;
use App\Models\Atendimento;
use App\Models\BackupSetting;
use Illuminate\Support\Str;
use App\Models\Caixa;
use App\Models\Camara;
use App\Models\Cargo;
use App\Models\CartaoConsumo;
use App\Models\CartaoConsumoHistorico;
use App\Models\CartaoConsumoMovimento;
use App\Models\Categoria;
use App\Models\CategoriaCargo;
use App\Models\CentroCusto;
use App\Models\Classe;
use App\Models\Cliente;
use App\Models\ConfiguracaoEmpressora;
use App\Models\ConfiguracaoRecursoHumano;
use App\Models\Consulta;
use App\Models\ConsultaItem;
use App\Models\Conta;
use App\Models\ContaBancaria;
use App\Models\ContaCliente;
use App\Models\ContaFornecedore;
use App\Models\Contrapartida;
use App\Models\Contrato;
use App\Models\ControloSistema;
use App\Models\Curso;
use App\Models\CursoModulo;
use App\Models\Departamento;
use App\Models\DepartamentoPasta;
use App\Models\Desconto;
use App\Models\DescontoContrato;
use App\Models\DescontoPacote;
use App\Models\Dispesa;
use App\Models\Distrito;
use App\Models\Documento;
use App\Models\EncomendaFornecedore;
use App\Models\Enfermeiro;
use App\Models\Entidade;
use App\Models\Equipa;
use App\Models\EquipamentoActivo;
use App\Models\Especialidade;
use App\Models\Estoque;
use App\Models\EvolucaoMedica;
use App\Models\Exame;
use App\Models\ExameItem;
use App\Models\Exercicio;
use App\Models\FacturaEncomendaFornecedor;
use App\Models\FacturaEncomendaFornecedorPagamento;
use App\Models\FacturaOriginal;
use App\Models\FichaConsulta;
use App\Models\FichaTriagem;
use App\Models\Formador;
use App\Models\Fornecedore;
use App\Models\Funcao;
use App\Models\Funcionario;
use App\Models\Gaveta;
use App\Models\HorarioFuncionario;
use App\Models\Internamento;
use App\Models\ItemFacturaOriginal;
use App\Models\ItemNotaCredito;
use App\Models\ItemPedidoCuzinha;
use App\Models\ItemRecibo;
use App\Models\ItemReserva;
use App\Models\ItemVenda;
use App\Models\ItensEncomenda;
use App\Models\ItensRequisicao;
use App\Models\ItensTransferencia;
use App\Models\Leito;
use App\Models\Loja;
use App\Models\LojaProduto;
use App\Models\Lote;
use App\Models\Marca;
use App\Models\MarcacaoAusencia;
use App\Models\MarcacaoFalta;
use App\Models\MarcacaoFeria;
use App\Models\Matricula;
use App\Models\Medico;
use App\Models\Membro;
use App\Models\MembroEquipa;
use App\Models\Mesa;
use App\Models\Modulo;
use App\Models\ModuloEntidade;
use App\Models\Morgue;
use App\Models\MorgueLiberacao;
use App\Models\MotivoAusencia;
use App\Models\MotivoReserva;
use App\Models\MotivoSaida;
use App\Models\Movimento;
use App\Models\MovimentoBanco;
use App\Models\MovimentoContaCliente;
use App\Models\Municipio;
use App\Models\SessaoCaixa;
use App\Models\NotaCredito;
use App\Models\Obito;
use App\Models\OperacaoFinanceiro;
use App\Models\Orcamento;
use App\Models\PacoteSalarial;
use App\Models\Pasta;
use App\Models\Pauta;
use App\Models\PedidoCuzinha;
use App\Models\Periodo;
use App\Models\PeriodoRendimento;
use App\Models\Pin;
use App\Models\PlanoTratamento;
use App\Models\Prioridade;
use App\Models\Processamento;
use App\Models\Produto;
use App\Models\ProdutoGrupoPreco;
use App\Models\Prova;
use App\Models\ProvaQuestao;
use App\Models\ProvaResposta;
use App\Models\Quarto;
use App\Models\QuartoTarefario;
use App\Models\Receita;
use App\Models\ReceitaMedica;
use App\Models\ReceitaMedicaItem;
use App\Models\Recibo;
use App\Models\Registro;
use App\Models\RenovacaoContrato;
use App\Models\Requisicao;
use App\Models\Reserva;
use App\Models\ReservaMesa;
use App\Models\SubParamentroExame;
use App\Models\Sala;
use App\Models\Seguradora;
use App\Models\SessaoTratamento;
use App\Models\Subconta;
use App\Models\Subsidio;
use App\Models\SubsidioContrato;
use App\Models\SubsidioPacote;
use App\Models\Tarefario;
use App\Models\TaxaIRT;
use App\Models\TipoAtendimento;
use App\Models\TipoContrato;
use App\Models\TipoCredito;
use App\Models\TipoFuncionario;
use App\Models\TipoProcessamento;
use App\Models\TipoQuarto;
use App\Models\Plano;
use App\Models\Profissao;
use App\Models\Provincia;
use App\Models\TipoEntidade;
use App\Models\TipoRendimento;
use App\Models\Turma;
use App\Models\TurmaAluno;
use App\Models\TurmaFormador;
use App\Models\Turno;
use App\Models\User;
use App\Models\UserLoja;
use App\Models\Variacao;
use App\Models\Venda;
use App\Models\Video;
use App\Services\DREService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;


use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmpresaController extends Controller
{
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->level  == 2) {
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [2])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        } else if ($user->level == 3) {
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [1, 2, 3])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }

        $head = [
            "titulo" => "lista de empresas",
            "descricao" => env('APP_NAME'),
            "empresas" => $empresas,
            "user" => $user,
        ];

        return view('admin.empresas.index', $head);
    }

    public function nosso_empresas_pdf()
    {

        $user = auth()->user();

        if ($user->level  == 2) {
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [2])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        } else if ($user->level == 3) {
            $empresas = Entidade::with(['tipo_entidade', 'controle'])
                ->whereIn('level', [1, 2, 3])
                ->where('id', '!=', Auth::user()->entidade_id)
                ->orderBy('nome', 'asc')
                ->get();
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "titulo" => "Nossas empresas | clientes",
            "descricao" => env('APP_NAME'),
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "empresas" => $empresas,
            "user" => $user,
        ];

        $pdf = PDF::loadView('admin.empresas.nossas-empresas', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $user = auth()->user();

        $users = User::with([
            'company' => function ($query) use ($user) {
                if ($user->level == 2) {
                    $query->where('level', 2); // Apenas o nível 2
                } elseif ($user->level == 3) {
                    $query->whereIn('level', [1, 2, 3]); // Níveis 1, 2 e 3
                }
            },
            'company.tipo_entidade' // Relacionamento da empresa com tipo_entidade
        ])
            ->where('level', 1) // Filtro principal para usuários
            ->get();


        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "users" => $users,
            "user" => $user,
        ];

        return view('admin.empresas.home', $head);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function nosso_utilizadores_pdf()
    {
        $user = auth()->user();

        $users = User::with([
            'company' => function ($query) use ($user) {
                if ($user->level == 2) {
                    $query->where('level', 2); // Apenas o nível 2
                } elseif ($user->level == 3) {
                    $query->whereIn('level', [1, 2, 3]); // Níveis 1, 2 e 3
                }
            },
            'company.tipo_entidade' // Relacionamento da empresa com tipo_entidade
        ])
            ->where('level', 1) // Filtro principal para usuários
            ->get();

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $head = [
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "users" => $users,
            "user" => $user,
        ];

        $pdf = Pdf::loadView('admin.empresas.nossos-utilizadores', $head);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tipos_entidade = TipoEntidade::where('status', 'activo')->get();
        $provincias = Provincia::get();
        $municipios = Municipio::get();

        $funcoes = Funcao::get();
        $profissoes = Profissao::get();
        $planos = Plano::get();

        $membros = Membro::get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),

            "tipos_entidade" => $tipos_entidade,
            "provincias" => $provincias,
            "municipios" => $municipios,
            "funcoes" => $funcoes,
            "profissoes" => $profissoes,
            "planos" => $planos,
            "membros" => $membros,
        ];

        return view('admin.empresas.create', $head);
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
            'empresa' => 'required|string',
            'nif' => 'required',
            'membro_id' => 'required',
        ]);

        $dados = $request->all();

        try {
            DB::beginTransaction();

            $token = Str::random(64);
            // Gerar uma sigla única
            $sigla = Entidade::generateUniqueSigla();

            $entidade = Entidade::create([
                'nome' => $dados['empresa'] ?? "",
                'sigla' => $sigla,
                'nif' => $dados['nif'] ?? "",
                'tipo_id' => $dados['tipo_negocio'] ?? 1,
                'tipo_empresa' => "Juridica",
                'morada' => $dados['residente'] ?? "",
                'status' => "desactivo",
                'codigo_postal' => NULL,
                'cidade' => NULL,
                'conservatoria' => NULL,
                'capital_social' => NULL,
                'nome_comercial' => NULL,
                'slogan' => NULL,
                'plano_id' => $request->plano_id,
                'membro_id' => $request->membro_id,
                'logotipo' => NULL,
                'municipio_id' => $dados['municipio_id'] ?? "",
                'provincia_id' => $dados['provincia_id'] ?? "",
                'pais' => NULL,
                'moeda' => NULL,
                'taxa_iva' => NULL,
                'motivo_isencao' => NULL,
                "email" => $dados['email_empresa'],
                'imposto_id' => NULL,
                'motivo_id' => NULL,
                'telefone' => $dados['telefone_empresa'],
                'website' => NULL,
                'promocoes_email' => false,
                'novidade_email' => false,
            ]);

            $membro = Membro::findOrFail($request->membro_id);

            $user = User::findOrFail($membro->user_id);
            $user->entidade_id = $entidade->id;

            $setting = BackupSetting::create([
                'user_id' => $user->id,
                'folder_path' => null,
                'enabled' => 0,
                'retain' => 24,
                'frequency_minutes' => 120,
                'last_run_at' => null,
                'tipo_mysql' => "padrao",
                'entidade_id' => $entidade->id
            ]);

            $role = Role::create(['name' => "{$entidade->sigla} - Administrador Geral", 'entidade_id' => $entidade->id]);
            // $permission = Permission::findByName("controle permissoes", "web");
            $permissions = Permission::get();
            foreach ($permissions as $permiss) {
                $role->givePermissionTo($permiss);
            }
            $user->roles()->attach($role);
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createControle()
    {
        $empresas = Entidade::with(['tipo_entidade', 'controle'])->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresas" => $empresas,
        ];

        return view('admin.empresas.createControle', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeControle(Request $request)
    {
        $empresa = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($request->empresa_id);

        if (!$empresa->controle) {
            $controle = ControloSistema::create([
                "inicio" => $request->inicio,
                "final" => $request->final,
                "entidade_id" => $request->empresa_id,
                "user_id" => Auth::user()->id,
            ]);

            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        }

        return redirect()->route('empresas.create')->with("warning", "Já existe informações de controle de Licença para essa empresa, actualiza simplesmente os dados!");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $empresa = Entidade::with(['lojas.caixas', 'tipo_entidade', 'controle', 'plano', 'caixas'])->findOrFail($id);

        $totalCliente = Cliente::where('entidade_id', $empresa->id)->count();
        $totalFornecedores = Fornecedore::where('entidade_id', $empresa->id)->count();
        $totalFuncionarios = Funcionario::where('entidade_id', $empresa->id)->count();
        $totalAlunos = Aluno::where('entidade_id', $empresa->id)->count();
        $totalFormadores = Formador::where('entidade_id', $empresa->id)->count();

        $receita = OperacaoFinanceiro::where('type', 'R')
            ->where('entidade_id', $empresa->id)
            ->sum('motante');

        $despesa = OperacaoFinanceiro::where('type', 'D')
            ->where('entidade_id', $empresa->id)
            ->sum('motante');

        $lucro = $receita - $despesa;

        $margem = 0;
        if (($receita ?? 0) > 0) {
            $margem = (($lucro ?? 0) / $receita) * 100;
        }

        $planos = Plano::get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "totalCliente" => $totalCliente,
            "totalFornecedores" => $totalFornecedores,
            "totalFuncionarios" => $totalFuncionarios,
            "totalAlunos" => $totalAlunos,
            "totalFormadores" => $totalFormadores,
            "planos" => $planos,

            'receita' => $receita,
            'despesa' => $despesa,
            'lucro' => $lucro,
            'margem' => $margem,

        ];

        return view('admin.empresas.show', $head);
    }

    public function showLoja(string $empresa_id, string $loja_id)
    {
        $empresa = Entidade::findOrFail($empresa_id);
        $loja = Loja::with(['caixas'])->findOrFail($loja_id);

        $receita = OperacaoFinanceiro::where('type', 'R')
            ->where('entidade_id', $empresa->id)
            ->where('loja_id', $loja->id)
            ->sum('motante');

        $despesa = OperacaoFinanceiro::where('type', 'D')
            ->where('entidade_id', $empresa->id)
            ->where('loja_id', $loja->id)
            ->sum('motante');

        $lucro = $receita - $despesa;

        $margem = 0;
        if (($receita ?? 0) > 0) {
            $margem = (($lucro ?? 0) / $receita) * 100;
        }

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
            "loja" => $loja,
            "receita" => $receita,
            "despesa" => $despesa,
            "lucro" => $lucro,
            "margem" => $margem,
        ];

        return view('admin.empresas.show-loja', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actualizar_modulos($id)
    {
        //
        $entidade = Entidade::with(['empresa_modulos'])->findOrFail($id);

        $entidade_permissions = $entidade->empresa_modulos->pluck('id')->toArray();

        $head = [
            "titulo" => "Actualização de Modulos | Entidades",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "modulos" => Modulo::whereIn('tipo', ['Empresa'])->get(),
            "entidade_permissions" => $entidade_permissions
        ];

        return view('admin.empresas.actualizar-modulos', $head);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function actualizar_modulos_post(Request $request)
    {
        //
        $request->validate([
            'entidade_id' => 'required',
        ]);

        $entidade = Entidade::with(['empresa_modulos'])->findOrFail($request->entidade_id)->empresa_modulos;
        $entidade_update = Entidade::findOrFail($request->entidade_id);

        try {
            DB::beginTransaction();

            foreach ($entidade as $item) {
                ModuloEntidade::where('entidade_id', $request->entidade_id)
                    ->where('modulo_id', $item->id)
                    ->forceDelete();
            }

            $entidade_update->tipo_facturacao = $request->tipo_facturacao;
            $entidade_update->update();

            foreach ($request->modulo_id as $item) {
                $verificar = ModuloEntidade::where('entidade_id', $request->entidade_id)->where('modulo_id', $item)->first();
                if (!$verificar) {
                    ModuloEntidade::create([
                        'entidade_id' => $request->entidade_id,
                        'modulo_id' => $item
                    ]);
                }
            }

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
    public function controlo($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->level = 3;
        $empresa->update();

        return redirect()->route('empresas.index')->with("success", "Controlo do Ndoma!");
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->status = 'desactivo';
        $empresa->update();

        if ($empresa->update()) {
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        } else {
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function actvar($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->status = 'activo';
        $empresa->update();

        if ($empresa->update()) {
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        } else {
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $empresa = Entidade::with(['tipo_entidade', 'controle'])->findOrFail($id);

        $head = [
            "titulo" => "Actualizar data de validação da empresa",
            "descricao" => env('APP_NAME'),
            "empresa" => $empresa,
        ];

        return view('admin.empresas.edit', $head);
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
        $controle = ControloSistema::findOrFail($id);

        $controle->inicio = $request->inicio;
        $controle->final = $request->final;

        if ($controle->update()) {
            return redirect()->route('empresas.index')->with("success", "Dados Actualizados com Sucesso!");
        } else {
            return redirect()->route('empresas.edit')->with("warning", "Erro ao tentar Actualizar Empresa");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $empresa = Entidade::findOrFail($id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            Agendamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Aluno::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            AlunoConteudo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Andar::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            AnoLectivo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Anuncio::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            AnuncioUser::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            AnuncioAdmin::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            AnuncioProva::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ArquivoPasta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Atendimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ContaBancaria::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            SessaoCaixa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Caixa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Camara::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Cargo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CartaoConsumo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CartaoConsumoMovimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CartaoConsumoHistorico::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Categoria::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CategoriaCargo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CentroCusto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Classe::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Cliente::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ConfiguracaoEmpressora::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ConfiguracaoRecursoHumano::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Consulta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ConsultaItem::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Conta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ContaCliente::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ContaFornecedore::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Contrato::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Contrapartida::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Curso::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            CursoModulo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Departamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            DepartamentoPasta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Desconto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            DescontoContrato::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            DescontoPacote::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            EncomendaFornecedore::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Documento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            EquipamentoActivo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Equipa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Especialidade::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Estoque::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            EvolucaoMedica::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Exame::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ExameItem::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Exercicio::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            FacturaOriginal::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            FacturaEncomendaFornecedor::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            FacturaEncomendaFornecedorPagamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            FichaTriagem::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            FichaConsulta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Formador::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TurmaFormador::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Fornecedore::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Funcionario::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Gaveta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            HorarioFuncionario::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Internamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemFacturaOriginal::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemNotaCredito::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemPedidoCuzinha::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemRecibo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemReserva::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItensEncomenda::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItensRequisicao::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItensTransferencia::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ItemVenda::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Leito::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Loja::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            LojaProduto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Lote::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Marca::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MarcacaoFalta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MarcacaoFeria::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MarcacaoAusencia::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Matricula::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Medico::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MembroEquipa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Mesa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Morgue::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MorgueLiberacao::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MotivoAusencia::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MotivoReserva::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MotivoSaida::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Movimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MovimentoBanco::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            MovimentoContaCliente::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            NotaCredito::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Obito::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            OperacaoFinanceiro::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Orcamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            PacoteSalarial::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Pasta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Pauta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            PedidoCuzinha::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Periodo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            PeriodoRendimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Pin::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            PlanoTratamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Prioridade::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Processamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Produto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ProdutoGrupoPreco::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Prova::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ProvaQuestao::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ProvaResposta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Quarto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            QuartoTarefario::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Dispesa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Receita::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ReceitaMedica::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ReceitaMedicaItem::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Recibo::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Registro::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            RenovacaoContrato::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Requisicao::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Reserva::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            ReservaMesa::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Sala::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Seguradora::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            SessaoTratamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Subconta::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Subsidio::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            SubsidioContrato::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            SubsidioPacote::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Tarefario::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TaxaIRT::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoContrato::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoFuncionario::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoProcessamento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoAtendimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoCredito::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoRendimento::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TipoQuarto::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            SubParamentroExame::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Turma::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TurmaAluno::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            TurmaFormador::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Turno::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            UserLoja::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Variacao::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Venda::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            Video::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();
            User::withTrashed()->where('entidade_id', $empresa->id)->forceDelete();

            $empresa->forceDelete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return redirect()->route('empresas.index')->with("success", "Empresa Eliminada com sucesso!");
    }

    /**
     * @param string $id
     */
    public function exportarFLuxoCaixa(string $id)
    {
        $dre = app(DREService::class)->generate($id);

        $empresa = Entidade::findOrFail($id);

        $pdf = Pdf::loadView('exports.empresas.dre', compact('dre', 'empresa'));

        return $pdf->download('dre.pdf');
    }

    public function exportarFLuxoLoja(string $empresa_id, string $loja_id)
    {
        $dre = app(DREService::class)->generate($empresa_id, $loja_id);

        $empresa = Entidade::findOrFail($empresa_id);
        $loja = Loja::findOrFail($loja_id);

        $pdf = Pdf::loadView('exports.empresas.dre-loja', compact('dre', 'empresa', 'loja'));

        return $pdf->download('dre.pdf');
    }

    public function storeCaixa(Request $request)
    {
        $entidade = Entidade::findOrFail($request->empresa_id);
        $loja = Loja::findOrFail($request->loja_id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $code = uniqid(time());
            $code_c = uniqid(time() + 1);
            $nova_conta = "";

            $classe = Classe::updateOrCreate(
                [
                    'conta' => 'Classe 2',
                    'entidade_id' => $entidade->id
                ],
                [
                    'nome' => 'Existências',
                    'status' => 'activo',
                    'conta' => 'Classe 2',
                    'sigla' => "EX",
                    'type' => "P",
                    'entidade_id' => $entidade->id,
                    'user_id' => Auth::user()->id
                ]
            );

            $conta = Conta::updateOrCreate(
                [
                    'conta' => '45',
                    'entidade_id' => $entidade->id
                ],
                [
                    'nome' => 'Caixa',
                    'status' => 'activo',
                    'conta' => '45',
                    'serie' => 1,
                    'classe_id' => $classe->id,
                    'entidade_id' => $entidade->id,
                    'user_id' => Auth::user()->id
                ]
            );

            $serie =  "45.1.";

            $subc_ = Subconta::where("numero", "like", "{$serie}%")->where("entidade_id", $entidade->id)->count() + 1;
            $nova_conta =  $serie . "{$subc_}";

            $subconta = Subconta::create([
                "entidade_id" => $entidade->id,
                "numero" => $nova_conta,
                "nome" => $request->nome,
                "tipo_conta" => "M",
                "code" => $code,
                "status" => "activo",
                "conta_id" => $conta->id,
                "user_id" => Auth::user()->id,
            ]);

            // Caixa principal
            $caixa = Caixa::updateOrCreate([
                "nome" => $request->nome,
                "conta" => $nova_conta,
                "code" => $code,
                "code_caixa" => $code_c,
                "status" => "fechado",
                "status_admin" => $request->status_admin,
                "user_id" => Auth::user()->id,
                "loja_id" => $loja->id,
                "entidade_id" => $entidade->id,
                "subconta_id" => $subconta->id,
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true,
            'caixa' => $caixa
        ]);
    }
}
