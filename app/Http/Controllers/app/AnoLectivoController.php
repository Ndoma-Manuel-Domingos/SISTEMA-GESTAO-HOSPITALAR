<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\AnoLectivo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AnoLectivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $anos_lectivos = AnoLectivo::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "anos_lectivos" => $anos_lectivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.index', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->can('criar todos') && !$user->can('criar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.create', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $request->validate([
            'nome' => 'required|string',
            'sigla' => 'required|string',
            'data_inicio' => 'required',
            'data_final' => 'required',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $verificar = AnoLectivo::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();

            if ($verificar && $request->status == "activo") {
                return response()->json(['message' => "Não pode ter dois anos lectivo activo no momento!"], 404);
            } else {
                $ano_lectivo = AnoLectivo::create([
                    'nome' => $request->nome,
                    'sigla' => $request->sigla,
                    'status' => $request->status,
                    'data_inicio' => $request->data_inicio,
                    'data_final' => $request->data_final,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
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
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $ano_lectivo = AnoLectivo::findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "ano_lectivo" => $ano_lectivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        $ano_lectivo = AnoLectivo::findOrFail($id);

        $head = [
            "titulo" =>  __('messages.editar'),
            "descricao" => env('APP_NAME'),
            "ano_lectivo" => $ano_lectivo,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.anos-lectivos.edit', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'data_inicio' => 'required',
            'data_final' => 'required',
            'sigla' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $ano_lectivo = AnoLectivo::findOrFail($id);

            $ano_lectivo->nome = $request->nome;
            $ano_lectivo->sigla = $request->sigla;
            $ano_lectivo->data_inicio = $request->data_inicio;
            $ano_lectivo->data_final = $request->data_final;
            $ano_lectivo->status = $request->status;

            $ano_lectivo->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $ano_lectivo = AnoLectivo::findOrFail($id);
            $ano_lectivo->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['message' => 'Dados Excluido com sucesso!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mudar_status($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar ano lectivo')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $ano_lectivo = AnoLectivo::findOrFail($id);

            if ($ano_lectivo->status == "activo") {
                $ano_lectivo->status = 'desactivo';
                $ano_lectivo->update();
            } else {

                $ano_lectivos = AnoLectivo::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->get();

                foreach ($ano_lectivos as $item) {
                    $update = AnoLectivo::findOrFail($item->id);
                    $update->status = 'desactivo';
                    $update->update();
                }

                $ano_lectivo->status = 'activo';
                $ano_lectivo->update();
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

        return response()->json(['message' => 'Dados actualizados com sucesso!'], 200);
    }
}
