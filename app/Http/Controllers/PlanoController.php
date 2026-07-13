<?php

namespace App\Http\Controllers;

use App\Models\Entidade;
use App\Models\Plano;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $planos = Plano::latest()->get();

        return view('admin.planos.index', compact('planos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.planos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Plano::create([
            'nome' => $request->nome,
            'valor_mensal' => $request->valor_mensal,
            'dia_vencimento' =>  $request->dia_vencimento,
            'multa_percentual' => $request->multa_percentual,
            'juros_diario' => $request->juros_diario
        ]);

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano criado');
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
        $plano = Plano::findOrFail($id);

        return view('admin.planos.edit',  compact('plano'));
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
        $plano = Plano::findOrFail($id);

        $plano->update([
            'nome' => $request->nome,
            'valor_mensal' => $request->valor_mensal,
            'dia_vencimento' =>  $request->dia_vencimento,
            'multa_percentual' => $request->multa_percentual,
            'juros_diario' => $request->juros_diario
        ]);

        return redirect()
            ->route('planos.index')
            ->with('success', 'Plano atualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plano = Plano::findOrFail($id);

        $plano->delete();

        return back()->with('success', 'Plano removido');
    }


    /*
    |--------------------------------------------------------------------------
    | ASSOCIAR PLANO
    |--------------------------------------------------------------------------
    */

    public function associarPlano(Request $request, string $id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->plano_id = $request->plano_id;
        $empresa->save();

        return back()->with('success', 'Plano associado');
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVER PLANO
    |--------------------------------------------------------------------------
    */

    public function removerPlano($id)
    {
        $empresa = Entidade::findOrFail($id);
        $empresa->plano_id = null;
        $empresa->save();

        return back()->with('success', 'Plano removido');
    }

    /*|--------------------------------------------------------------------------
    | BLOQUEAR EMPRESA
    |--------------------------------------------------------------------------
    */

    public function bloquearEmpresa(string $id)
    {
        $empresa = Entidade::findOrFail($id);

        if ($empresa->status == 'activo') {
            $empresa->status = 'desactivo';
        } else {
            $empresa->status = 'activo';
        }

        $empresa->save();

        return back()->with('success', 'Status atualizado');
    }
}
