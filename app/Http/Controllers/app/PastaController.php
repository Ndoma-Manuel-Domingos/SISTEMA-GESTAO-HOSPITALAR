<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\ArquivoPasta;
use App\Models\DepartamentoPasta;
use App\Models\Pasta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use Ramsey\Uuid\Uuid;

class PastaController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('criar todos') && !$user->can('criar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
            'departamento_id' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $departamento = DepartamentoPasta::findOrFail($request->departamento_id);

            Pasta::create([
                'entidade_id' => $entidade->empresa->id,
                'nome' => $request->nome,
                'code' => Uuid::uuid4(),
                'status' => $request->status,
                'parent_id' => NULL,
                'departamento_id' => $departamento->id,
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
    public function show($departamento_id, $pasta_id)
    {
        $user = auth()->user();

        if (!$user->can('listar todos') && !$user->can('listar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $departamento = DepartamentoPasta::where('code', $departamento_id)->where('entidade_id', '=', $entidade->empresa->id)->first();
        $pasta = Pasta::where('code', $pasta_id)->where('entidade_id', '=', $entidade->empresa->id)->first();

        $subpastas = Pasta::where('parent_id', $pasta->id)->where('entidade_id', '=', $entidade->empresa->id)->get();
        $arquivos = ArquivoPasta::where('parent_id', $pasta->id)->where('entidade_id', '=', $entidade->empresa->id)->get();

        $head = [
            "titulo" => __('messages.mais_detalhes'),
            "descricao" => env('APP_NAME'),
            "departamento" => $departamento,
            "pasta" => $pasta,
            "subpastas" => $subpastas,
            "arquivos" => $arquivos,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.pastas.show', $head);
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

        if (!$user->can('editar todos') && !$user->can('editar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $pasta = Pasta::findOrFail($id);

        return response()->json(['success' => true, 'data' => $pasta], 200);
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

        if (!$user->can('editar todos') && !$user->can('editar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        $request->validate([
            'nome' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $pasta = Pasta::findOrFail($id);
            $pasta->nome = $request->nome;
            $pasta->status = $request->status;
            $pasta->update();

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

        if (!$user->can('eliminar todos') && !$user->can('eliminar departamento')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $pasta = Pasta::findOrFail($id);

            $subpastas = Pasta::where('parent_id', $pasta->id)->where('entidade_id', $user->entidade_id)->get();

            foreach ($subpastas as $item) {
                Pasta::findOrFail($item->id)->delete();
            }

            $pasta->delete();

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
