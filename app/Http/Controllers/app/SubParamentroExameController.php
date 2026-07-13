<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Categoria;
use App\Models\ParamentroExame;
use App\Models\Produto;
use App\Models\SubParamentroExame;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Ramsey\Uuid\Uuid;

class SubParamentroExameController extends Controller
{
    use TraitHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $categoria = Categoria::whereIn('categoria', ['Exames', 'Exame', 'exames', 'exame'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)->get();
        $parametros = ParamentroExame::with(['exame'])->where('entidade_id', $entidade->empresa->id)->get();

        $resultado_exame = SubParamentroExame::when($request->exame_id, function ($query, $value) {
            $query->where('exame_id', $value);
        })
            ->with(['paramentro.exame'])
            ->where('entidade_id', $entidade->entidade_id)
            ->get();

        $head = [
            "titulo" => "Resultados de Exames",
            "descricao" => env('APP_NAME'),
            "tipos" => $resultado_exame,
            "produtos" => $produtos,
            "parametros" => $parametros,
            "requests" => $request->all('exame_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.exames.parametros-subparametros-exames', $head);
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
        $request->validate([
            'exame_id' => 'required',
            'nome' => 'required',
            'tipo' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

            $opcoes = "";
            if ($request->tipo == 'lista') {
                $opcoes = json_encode(
                    preg_split(
                        "/\r\n|\n|\r/",
                        trim($request->opcoes)
                    )
                );
            }

            $proximaOrdem = SubParamentroExame::where('parametro_id', $request->parametro_id)
                ->max('ordem');

            $proximaOrdem = $proximaOrdem ? $proximaOrdem + 1 : 1;

            SubParamentroExame::create([
                'exame_id' => $request->exame_id,
                'parametro_id' => $request->parametro_id,
                'nome' => $request->nome,
                'codigo' => $request->codigo,
                'tipo' => $request->tipo,
                'unidade' => $request->unidade,
                'valor_referencia' => $request->valor_referencia,
                'valor_minimo' => $request->valor_minimo,
                'valor_maximo' => $request->valor_maximo,
                'texto_sim' => $request->texto_sim,
                'texto_nao' => $request->texto_nao,
                'opcoes' => $opcoes,
                'ordem' => $proximaOrdem,
                'tamanho_maximo' => $request->tamanho_maximo,
                'valor_padrao' => $request->valor_padrao,
                'permitir_passado' => $request->permitir_passado ?? 0,
                'permitir_futuro' => $request->permitir_futuro ?? 0,
                'linhas' => $request->linhas,
                'extensoes_permitidas' => $request->extensoes_permitidas,
                'tamanho_max_arquivo' => $request->tamanho_max_arquivo,
                'obrigatorio' => $request->obrigatorio ?? 0,
                'activo' => $request->activo ?? 1,
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

        $resultado_exame = SubParamentroExame::findOrFail($id);
        return response()->json(['success' => true, 'data' => $resultado_exame], 200);
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
            'exame_id' => 'required',
            'nome' => 'required',
            'tipo' => 'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $parametro = SubParamentroExame::findOrFail($id);

            $opcoes = "";

            if ($request->tipo == 'lista') {
                $opcoes = json_encode(
                    preg_split(
                        "/\r\n|\n|\r/",
                        trim($request->opcoes)
                    )
                );
            }

            $parametro->update([
                'exame_id' => $request->exame_id,
                'nome' => $request->nome,
                'codigo' => $request->codigo,
                'tipo' => $request->tipo,
                'unidade' => $request->unidade,
                'valor_referencia' => $request->valor_referencia,
                'valor_minimo' => $request->valor_minimo,
                'valor_maximo' => $request->valor_maximo,
                'opcoes' => $opcoes,
                'ordem' => $request->ordem,
                'texto_sim' => $request->texto_sim,
                'texto_nao' => $request->texto_nao,
                'tamanho_maximo' => $request->tamanho_maximo,
                'valor_padrao' => $request->valor_padrao,
                'permitir_passado' => $request->permitir_passado ?? 0,
                'permitir_futuro' => $request->permitir_futuro ?? 0,
                'linhas' => $request->linhas,
                'extensoes_permitidas' => $request->extensoes_permitidas,
                'tamanho_max_arquivo' => $request->tamanho_max_arquivo,
                'obrigatorio' => $request->obrigatorio ?? 0,
                'activo' => $request->activo ?? 1
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
            $resultado_exame = SubParamentroExame::findOrFail($id);
            $resultado_exame->delete();
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

    public function export(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $exames = SubParamentroExame::when($request->exame_id, function ($query, $value) {
            $query->where('exame_id', $value);
        })
            ->with(['exame'])
            ->where('entidade_id', $entidade->entidade_id)
            ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $exame = Produto::find($request->exame_id);

        $head = [
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'titulo' => "Paramento de Exame: " . $exame ? $exame->nome : "",
            'descricao' => "",
            'exames' => $exames,
            'exame' => $exame,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = Pdf::loadView('dashboard.exames.tipo-resultado-exames-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }
}
