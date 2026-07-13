<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\ParamentroExame;
use App\Models\Produto;
use App\Models\SubParamentroExame;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParamentroExameController extends Controller
{
    use TraitHelpers;

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

        $request->validate([
            'exame_parametro_id' => 'required',
            'nome_parametro' => 'required|string',
            'ordem_parametro' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $proximaOrdem = ParamentroExame::where('exame_id', $request->exame_parametro_id)
                ->max('ordem');

            $proximaOrdem = $proximaOrdem ? $proximaOrdem + 1 : 1;

            ParamentroExame::create([
                'exame_id' => $request->exame_parametro_id,
                'nome' => $request->nome_parametro,
                'ordem' => $proximaOrdem,
                'observacao' => $request->observacoes_paramentro,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

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

        $parametro = ParamentroExame::findOrFail($id);
        return response()->json(['success' => true, 'data' => $parametro], 200);
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
        if (!$user->can('editar todos')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'exame_parametro_id' => 'required',
            'nome_parametro' => 'required',
            'ordem_parametro' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $parametro = ParamentroExame::findOrFail($id);

            $parametro->update([
                'exame_id' => $request->exame_parametro_id,
                'nome' => $request->nome_parametro,
                'ordem' => $request->ordem_parametro,
                'observacao' => $request->observacoes_paramentro
            ]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
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
            $parametro = ParamentroExame::with('subparamentros')->findOrFail($id);

            $parametro->subparamentros()->delete();

            $parametro->delete();

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }
        return response()->json(['success' => true, 'message' => "Dados Excluídos com sucesso!"], 200);
    }


    public function buscarPorExame(string $id)
    {
        $exame = Produto::findOrFail($id);

        $parametros = $exame->paramentros()
            ->select('paramentros_exames.id', 'paramentros_exames.nome')
            ->get();

        return response()->json($parametros);
    }
}
