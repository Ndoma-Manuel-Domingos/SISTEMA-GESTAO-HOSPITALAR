<?php

namespace App\Http\Controllers;

use App\Models\CategoriaInvestimento;
use App\Models\Investimento;
use App\Services\InvestimentoService;
use Illuminate\Http\Request;

class InvestimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InvestimentoService $service)
    {
        $investimentos = Investimento::with('categoria')
            ->latest()
            ->get();

        $totalInvestido = Investimento::sum('valor_investido');

        $totalValorActual = Investimento::sum('valor_atual');

        $lucro = $totalValorActual - $totalInvestido;

        return view(
            'investimentos.index',
            compact(
                'investimentos',
                'totalInvestido',
                'totalValorActual',
                'lucro'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = CategoriaInvestimento::all();

        return view(
            'investimentos.create',
            compact('categorias')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Investimento::create(
            $request->all()
        );

        return redirect()
            ->route('investimentos.index')
            ->with(
                'success',
                'Investimento criado'
            );
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
