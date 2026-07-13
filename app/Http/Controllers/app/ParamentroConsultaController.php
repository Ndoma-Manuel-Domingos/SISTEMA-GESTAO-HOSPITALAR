<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitHelpers;
use App\Models\Categoria;
use App\Models\ParamentroConsulta;
use App\Models\Produto;
use App\Models\ParamentroExame;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Ramsey\Uuid\Uuid;

class ParamentroConsultaController extends Controller
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
        
        $categoria = Categoria::whereIn('categoria', ['Consultas', 'Consulta', 'consultas', 'consulta'])->where('entidade_id', $entidade->empresa->id)->pluck("id");

        $produtos = Produto::whereIn('categoria_id', $categoria)->where('entidade_id', $entidade->empresa->id)->get();

        $resultado_consultas = ParamentroConsulta::when($request->consulta_id, function($query, $value){
            $query->where('consulta_id', $value);
        })
        ->with(['consulta'])
        ->where('entidade_id', $entidade->entidade_id)
        ->get();
        
        $head = [
            "titulo" => "Resultados de Exames",
            "descricao" => env('APP_NAME'),
            "tipos" => $resultado_consultas,
            "produtos" => $produtos,
            "requests" => $request->all('consulta_id'),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.consultas.paramentros-consultas', $head);
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
        
        if(!$user->can('criar todos')){
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        $request->validate([
            'consulta_id'=>'required',
            'nome'=>'required',
            'tipo'=>'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
            
            $opcoes = "";
            if($request->tipo == 'lista')
            {
                $opcoes = json_encode(
                    preg_split(
                        "/\r\n|\n|\r/",
                        trim($request->opcoes)
                    )
                );
            }
            
            ParamentroConsulta::create([
                'consulta_id' => $request->consulta_id,
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
                'ordem' => $request->ordem,
                'tamanho_maximo' => $request->tamanho_maximo,
                'valor_padrao' => $request->valor_padrao,
                'permitir_passado' => $request->permitir_passado ?? 0,
                'permitir_futuro' => $request->permitir_futuro ?? 0,
                'linhas' => $request->linhas,
                'extensoes_permitidas' => $request->extensoes_permitidas,
                'tamanho_max_arquivo' => $request->tamanho_max_arquivo,
                'obrigatorio'=>$request->obrigatorio ?? 0,
                'activo'=>$request->activo ?? 1,
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
    public function show($id)
    {
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
        
        if(!$user->can('editar todos')){
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $consulta = ParamentroConsulta::findOrFail($id);
        return response()->json(['success' => true, 'data' => $consulta], 200);
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
        if(!$user->can('editar todos')){
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        $request->validate([
            'consulta_id'=>'required',
            'nome'=>'required',
            'tipo'=>'required'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $parametro = ParamentroConsulta::findOrFail($id);
            
            $opcoes = "";
            
            if($request->tipo == 'lista')
            {
                $opcoes = json_encode(
                    preg_split(
                        "/\r\n|\n|\r/",
                        trim($request->opcoes)
                    )
                );
            }
            
            $parametro->update([
                'consulta_id' => $request->consulta_id,
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
                'obrigatorio'=>$request->obrigatorio ?? 0,
                'activo'=>$request->activo ?? 1
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
        
        if(!$user->can('eliminar todos')){
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
      
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui
            $resultado_consulta = ParamentroConsulta::findOrFail($id);
            $resultado_consulta->delete();
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
        
        $consultas = ParamentroExame::when($request->consulta_id, function($query, $value){
            $query->where('consulta_id', $value);
        })
        ->with(['consulta'])
        ->where('entidade_id', $entidade->entidade_id)
        ->get();

        // Caminho da imagem
        $logotipoPath = public_path("images/empresa/{$this->LOJA_ACTIVA_USER()->logotipo}");
        $temLogotipo = File::exists($logotipoPath);

        $consulta = Produto::find($request->consulta_id);

        $head = [
            "logotipo" => $temLogotipo ? $logotipoPath : public_path('/images/empresa/logo-default.png'),
            'titulo' => "Paramento de Consulta: " . $consulta ? $consulta->nome : "",
            'descricao' => "",
            'consultas' => $consultas,
            'consulta' => $consulta,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        $pdf = Pdf::loadView('dashboard.exames.tipo-resultado-exames-pdf', $head);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream();
    }

}
