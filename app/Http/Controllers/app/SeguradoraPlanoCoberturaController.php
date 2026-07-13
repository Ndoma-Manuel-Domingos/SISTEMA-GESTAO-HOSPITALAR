<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\SeguradoraPlanoCobertura;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

use PDF;

class SeguradoraPlanoCoberturaController extends Controller
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

        if (!$user->can('criar todos') && !$user->can('criar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'plano_id' => 'required|string',
            'servico_id' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            SeguradoraPlanoCobertura::create([
                'plano_id' => $request->plano_id,
                'servico_id' => $request->servico_id,
                'limite' => $request->limite,
                'copagamento' => $request->copagamento,
                'percentual' => $request->percentual,
                'status' => $request->status ?? true,
                'observacoes' => $request->observacoes,
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

        $plano = SeguradoraPlanoCobertura::findOrFail($id);

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
                'plano_id' => 'required|string',
                'servico_id' => 'required|string',
            ]);

            $cobertura = SeguradoraPlanoCobertura::findOrFail($id);

            $cobertura->plano_id = $request->plano_id;
            $cobertura->servico_id = $request->servico_id;
            $cobertura->limite = $request->limite;
            $cobertura->copagamento = $request->copagamento;
            $cobertura->percentual = $request->percentual;
            $cobertura->status = $request->status ?? true;
            $cobertura->observacoes = $request->observacoes;


            $cobertura->update();

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
        //
        $user = auth()->user();

        if (!$user->can('eliminar todos') && !$user->can('eliminar seguradora')) {
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $cobertura = SeguradoraPlanoCobertura::findOrFail($id);
            $cobertura->delete();

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
