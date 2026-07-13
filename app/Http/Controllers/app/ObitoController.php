<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Http\Controllers\TraitHelpers;
use App\Models\Atendimento;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Equipa;
use App\Models\EvolucaoMedica;
use App\Models\Internamento;
use App\Models\Leito;
use App\Models\Medico;
use App\Models\Obito;
use App\Models\Produto;
use App\Models\TipoAtendimento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

use PDF;
use phpseclib\Crypt\RSA;


class ObitoController extends Controller
{
    use TraitChavesSaft;
    use TraitHelpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $obitos = Obito::with(["atendimento", "paciente", "medico", "user", "entidade"])
            ->where("entidade_id", $entidade->entidade_id)
            ->orderBy("id", "desc")
            ->get();

        $head = [
            "titulo" => "Obitos",
            "descricao" => env("APP_NAME"),
            "obitos" => $obitos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.obitos.index", $head);
    }

    public function create(Request $request)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $leitos = Leito::where("status", "livre")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $produtos = Produto::where("tipo", "S")
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $equipas = Equipa::whereIn("status", ["desactiva"])
            ->where('entidade_id', $entidade->empresa->id)
            ->get();

        $tipos_atendimentos = TipoAtendimento::where('entidade_id', $entidade->empresa->id)
            ->get();

        $query = Cliente::where('entidade_id', $entidade->empresa->id);

        $atendimento = Atendimento::findOrFail($request->atendimento_id);

        $query->when($atendimento->cliente_id, function ($query, $value) {
            $query->where("id", $value);
        });

        $pacientes = $query->get();

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env("APP_NAME"),
            "pacientes" => $pacientes,
            "equipas" => $equipas,
            "leitos" => $leitos,
            "atendimento" => $atendimento,
            "tipos_atendimentos" => $tipos_atendimentos,
            "produtos" => $produtos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.internamentos.create", $head);
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

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $request->validate([
            'paciente_id' => 'required|string',
            'data_internacao' => 'required|date',
            'equipa_id' => 'required',
            'atendimento_id' => 'required',
            'produto_id' => 'required',
            'diagnostico_inicial' => 'required',
            'leito_id' => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $solicitacao = Internamento::where('entidade_id', $entidade->empresa->id)->count();
            $solicitacao = $solicitacao  + 1;

            $leito = Leito::findOrFail($request->leito_id);
            $produto = Produto::findOrFail($request->produto_id);

            $verificar = Internamento::where("status", "activo")
                ->where("paciente_id", $request->paciente_id)
                ->where("equipa_id", $request->equipa_id)
                ->where("leito_id", $request->leito_id)
                ->first();

            if (!$verificar) {
                $internamento = Internamento::create([
                    "numero" => "INTER - {$solicitacao}",
                    "status" => "activo", // "Activo","Alta","Obito"
                    "paciente_id" => $request->paciente_id,
                    "leito_id" => $request->leito_id,
                    "consulta_id" => $request->consulta_id ?? NULL,
                    "equipa_id" => $request->equipa_id,
                    "atendimento_id" => $request->atendimento_id, //origem do internamento
                    "produto_id" => $request->produto_id, // servico para area financeira
                    "motivo" => $request->motivo,
                    "total" => $produto->preco_venda,
                    "diagnostico_inicial" => $request->diagnostico_inicial,
                    "data_internacao" => $request->data_internacao,
                    "data_alta" => $request->data_alta,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);

                EvolucaoMedica::create([
                    "internamento_id" => $internamento->id,
                    "user_id" => Auth::user()->id,
                    "entidade_id" => $entidade->empresa->id,
                ]);
                
                $leito->status = "ocupada";
                $leito->update();
                
                $tipo_atendimento = TipoAtendimento::where("sigla", "Internamento")->where("entidade_id", $entidade->empresa->id)->first();
                
                $inicioDoDia = Carbon::parse($request->data_consulta)->startOfDay();
                $fimDoDia = Carbon::parse($request->data_consulta)->endOfDay();
                
                $total_atendimentos = Atendimento::whereBetween("created_at", [$inicioDoDia, $fimDoDia])->where("tipo_atendimento_id", $tipo_atendimento->id ?? "")->where("entidade_id", $entidade->empresa->id)->count();
                $total_atendimentos = $total_atendimentos  + 1;
                
                $sigla = $tipo_atendimento ? $tipo_atendimento->sigla : NULL;

                $atendimento = Atendimento::findOrFail($request->atendimento_id);
                $atendimento->status = "internamento";
                $atendimento->numero = "{$sigla} - {$total_atendimentos}";
                $atendimento->update();
                
                $consulta = Consulta::find($request->consulta_id);
                if($consulta){
                    $consulta->internamento_id = $internamento->id;
                    $consulta->update();
                }
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

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function comunicar_familiares($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {

            $obito = Obito::findOrFail($id);
            
            if($obito->comunicacao_obito == 1) {
                $status = 0;
            }else {
                $status = 1;
            }
            
            $obito->comunicacao_obito = $status;
            $obito->update();

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entregar_a_morte($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {

            $obito = Obito::findOrFail($id);
            $obito->status = "morgue";
            $obito->update();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function entreguar_a_familiares($id)
    {
        $user = auth()->user();

        if (!$user->can('criar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {

            $obito = Obito::findOrFail($id);
            $obito->status = "entregue";
            $obito->update();

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
    public function show($id)
    {
        $obito = Obito::with(["atendimento", "paciente", "medico", "user", "entidade"])->findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env("APP_NAME"),
            "obito" => $obito,
            "loja" => User::with(["empresa"])->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.obitos.show', $head);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function imprimir($id)
    {
        $internamento = Internamento::with(["atendimento.consultas", "atendimento.exames", "atendimento.triagem", "evolucao_medica", "tipo_atendimento", "produto", "leito", "paciente", "equipa", "user", "entidade"])->findOrFail($id);
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);
        
        $head = [
            "titulo" => "Ficha Técnica",
            "descricao" => env('APP_NAME'),
            "internamento" => $internamento,
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),

            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = PDF::loadView('dashboard.internamentos.ficha-tecnica', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
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

        if (!$user->can('editar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        //
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        
        $obito = Obito::with(["atendimento", "paciente", "medico", "user", "entidade"])->findOrFail($id);
        $medicos = Medico::with(["funcionario"])->where('entidade_id', $entidade->empresa->id)->get();
        $pacientes = Cliente::where('entidade_id', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.editar'),
            "descricao" => env("APP_NAME"),
            "entidade" => $entidade,
            "pacientes" => $pacientes,
            "medicos" => $medicos,
            "obito" => $obito,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];

        return view("dashboard.obitos.edit", $head);
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
        $user = auth()->user();

        if (!$user->can('editar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'paciente_id' => 'required|string',
            'data_obito' => 'required|date',
            'hora_obito' => 'required',
            'tipo_obito' => 'required',
            'local_obito' => 'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $obito = Obito::findOrFail($id);
            $obito->paciente_id = $request->paciente_id;
            $obito->medico_id = $request->medico_id;
            $obito->data_obito = $request->data_obito;
            $obito->hora_obito = $request->hora_obito;
            $obito->local_obito = $request->local_obito;
            $obito->tipo_obito = $request->tipo_obito;
            $obito->comunicacao_obito = $request->comunicacao_obito;
            $obito->causa_obito = $request->resumo;

            $obito->update();

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

        if (!$user->can('eliminar todos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $obito = Obito::findOrFail($id);
            $obito->delete();

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
