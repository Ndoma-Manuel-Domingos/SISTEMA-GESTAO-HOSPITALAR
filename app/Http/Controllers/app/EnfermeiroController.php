<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Medico;
use App\Models\Entidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnfermeiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $enfermeiros = Medico::with(['funcionario', 'especialidade'])->where("entidade_id", $entidade->empresa->id)
            ->whereIn('tipo', ['Enfermeiro'])
            ->orderBy('created_at', 'desc')
            ->get();

        $empresa = Entidade::with(['variacoes', 'clientes', 'marcas', 'categorias'])
            ->findOrFail($entidade->empresa->id);

        $head = [
            "titulo" => __('messages.listagem'),
            "descricao" => env('APP_NAME'),
            "enfermeiros" => $enfermeiros,
            "empresa" => $empresa,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.enfermeiros.index', $head);
    }
}
