<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Subsidio;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SubsidioController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar subsidio')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $subsidios = Subsidio::where("entidade_id", $entidade->empresa->id)->orderBy('created_at', 'desc')->get();

        $head = [
            "titulo" => "Subsídios",
            "descricao" => env('APP_NAME'),
            "subsidios" => $subsidios,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subsidios.index', $head);
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

        if (!$user->can('criar todos') && !$user->can('criar subsidio')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'irt' => 'required|string',
            'inss' => 'required|string',
            'limite_isencao' => 'required', #valor
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $count = Subsidio::where('entidade_id', $entidade->empresa->id)->count();

            Subsidio::create([
                'numero' => "S00" .  $count + 1,
                'entidade_id' => $entidade->empresa->id,
                'nome' => $request->nome,
                'irt' => $request->irt,
                'inss' => $request->inss,
                'limite_isencao' => $request->limite_isencao,
                'user_id' => Auth::user()->id,
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
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar subsidio')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $subsidio = Subsidio::findOrFail($id);

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "subsidio" => $subsidio,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.subsidios.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar subsidio')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $subsidio = Subsidio::findOrFail($id);

        return response()->json(['success' => true, 'data' => $subsidio], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar subsidio')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $subsidio = Subsidio::findOrFail($id);

            $subsidio->numero = "S00" . $subsidio->id;

            $subsidio->update($request->all());

            $subsidio->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar subsidio')) {

            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui  
            $subsidio = Subsidio::findOrFail($id);
            $subsidio->delete();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return response()->json(['success' => true, 'message' => "Dados Excluído com sucesso!"], 200);
    }
}
