<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Seguradora;
use App\Models\SeguradoraPlano;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class SeguradoraPlanoController extends Controller
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

        if (!$user->can('listar todos') && !$user->can('listar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $planos = SeguradoraPlano::with(['seguradora'])->where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $seguradoras = Seguradora::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => "Planos Seguradora",
            "descricao" => env('APP_NAME'),
            "planos" => $planos,
            "seguradoras" => $seguradoras,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.planos.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'seguradora_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            SeguradoraPlano::create([
                'seguradora_id' => $request->seguradora_id,
                'codigo' => $request->codigo,
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'tipo' => $request->tipo,
                'percentual_cobertura' => $request->percentual_cobertura,
                'percentual_coparticipacao' => $request->percentual_coparticipacao,
                'limite_anual' => $request->limite_anual,
                'limite_por_atendimento' => $request->limite_por_atendimento,
                'dias_carencia' => $request->dias_carencia,
                'necessita_autorizacao' => $request->necessita_autorizacao,
                'ativo' => $request->ativo,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
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

        if (!$user->can('listar todos') && !$user->can('listar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $plano = SeguradoraPlano::with(['seguradora', 'beneficiarios.beneficiario', 'coberturas.servico'])->findOrFail($id);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $produtos = Produto::where("entidade_id", $entidade->empresa->id)->get();
        $beneficiarios = Cliente::where("entidade_id", $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "plano" => $plano,
            "beneficiarios" => $beneficiarios,
            "produtos" => $produtos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.seguradoras.planos.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $plano = SeguradoraPlano::findOrFail($id);

        return response()->json(['success' => true, 'data' => $plano], 200);
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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $user = auth()->user();

            if (!$user->can('editar todos') && !$user->can('editar seguradora')) {
                return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
            }

            $request->validate([
                'nome' => 'required|string',
                'seguradora_id' => 'required|string',
            ]);

            $plano = SeguradoraPlano::findOrFail($id);

            $plano->seguradora_id = $request->seguradora_id;
            $plano->codigo = $request->codigo;
            $plano->nome = $request->nome;
            $plano->tipo = $request->tipo;
            $plano->descricao = $request->descricao;
            $plano->percentual_cobertura = $request->percentual_cobertura;
            $plano->percentual_coparticipacao = $request->percentual_coparticipacao;
            $plano->limite_anual = $request->limite_anual;
            $plano->limite_por_atendimento = $request->limite_por_atendimento;
            $plano->dias_carencia = $request->dias_carencia;
            $plano->necessita_autorizacao = $request->necessita_autorizacao;
            $plano->ativo = $request->ativo;

            $plano->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => "Dados salvos com sucesso", 'success' => true]);
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

        if (!$user->can('eliminar todos') && !$user->can('eliminar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        //
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $plano = SeguradoraPlano::findOrFail($id);
            $plano->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            dd('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);
    }
}
