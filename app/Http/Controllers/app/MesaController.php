<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Caixa;
use App\Models\Mesa;
use App\Models\Sala;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
        $mesas = Mesa::where("entidade_id", $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $head = [
            "titulo" => "Mesas",
            "descricao" => env('APP_NAME'),
            "mesas" => $mesas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.index', $head);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visualizacao_mesas()
    {
        $user = auth()->user();

        if (!$user->can('monitoramento de mesas')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $salas = Sala::with(["mesas.pedidos.produto"])
            ->where('entidade_id', $entidade->empresa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $mesas_reservadas = Mesa::where('solicitar_ocupacao', 'RESERVADA')->where('entidade_id', $entidade->empresa->id)->count();
        $mesas_disponivel = Mesa::where('solicitar_ocupacao', 'LIVRE')->where('entidade_id', $entidade->empresa->id)->count();
        $mesas_ocupadas = Mesa::where('solicitar_ocupacao', 'OCUPADA')->where('entidade_id', $entidade->empresa->id)->count();

        $checkCaixa = Caixa::where("active", true)
            ->where("status", "aberto")
            ->where('status_admin', 'liberado')
            ->where("user_open_id", Auth::user()->id)
            ->where("entidade_id", $entidade->empresa->id)
            ->first();


        $head = [
            "titulo" => "Monitoramento das Mesas",
            "descricao" => env('APP_NAME'),
            "checkCaixa" => $checkCaixa,
            "salas" => $salas,
            "mesas_reservadas" => $mesas_reservadas,
            "mesas_disponivel" => $mesas_disponivel,
            "mesas_ocupadas" => $mesas_ocupadas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.visualizacoes', $head);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if (!isset($request->createLoja)) {
            return redirect()->route('salas.index');
        }

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
            "sala_id" => $request->createLoja,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.create', $head);
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
            'nome' => 'required|string',
            "sala_id" =>  'required',
        ]);


        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $mesas = Mesa::create([
                "nome" => $request->nome,
                "ocupacao" => $request->ocupacao,
                "solicitar_ocupacao" => $request->solicitar_ocupacao,
                "sala_id" => $request->sala_id,
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
        $mesas = Mesa::findOrFail($id);
        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "mesa" => $mesas,
            "dados" => User::with("empresa")->findOrFail(Auth::user()->id),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.show', $head);
    }

    public function mudar_status_mesa($id)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $mesa = Mesa::findOrFail($id);
            $mesa->solicitar_ocupacao = "LIVRE";
            $mesa->update();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Mesa reiniciada com sucesso!", 'redirect' => route('mesas.visualizacao-mesas')], 200);
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
        $mesas = Mesa::findOrFail($id);

        $head = [
            "titulo" => "Mesas",
            "descricao" => env('APP_NAME'),
            "mesa" => $mesas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.mesas.edit', $head);
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
        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $mesas = Mesa::findOrFail($id);
            $mesas->update($request->all());

            $mesas->update();

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
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $mesas = Mesa::findOrFail($id);
            $mesas->delete();
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
}
