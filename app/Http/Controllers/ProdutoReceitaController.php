<?php

namespace App\Http\Controllers;

use App\Models\ProdutoReceita;
use App\Models\ProdutoReceitaItem;
use App\Models\Unidade;
use App\Models\User;
use App\Support\UnitConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProdutoReceitaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        try {
            DB::beginTransaction();

            $receita = ProdutoReceita::create([
                'produto_id' => $request->produto_id,
                'nome' => $request->nome,
                'rendimento_base' => $request->rendimento_base,
                'peso' => $request->peso,
                'user_id' => Auth::user()->id,
                'entidade_id' => $entidade->empresa->id,
            ]);

            foreach ($request->items as $item) {
                
                $unidade = Unidade::findOrFail($item['unidade']);
                
                ProdutoReceitaItem::create([
                    'receita_id' => $receita->id,
                    'ingrediente_id' => $item['ingrediente_id'],
                    'quantidade' => $item['quantidade'],
                    'quantidade_gramas' => UnitConverter::converterParaBase($item['quantidade'], $unidade),
                    'unidade_id' =>  $unidade->id,
                    'user_id' => Auth::user()->id,
                    'entidade_id' => $entidade->empresa->id,
                ]);
                
            }
            
            //
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json([
            'success' => true,
            'message' => 'Receita criada com sucesso!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    }
}
