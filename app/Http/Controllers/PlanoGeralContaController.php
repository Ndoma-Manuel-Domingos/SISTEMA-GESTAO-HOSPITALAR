<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Conta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanoGeralContaController extends Controller
{
    //
    public function index(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $classes = Classe::with(['contas.subcontas'])->where('entidade_id', $entidade->empresa->id)->get();
        $contas = Conta::where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => __('messages.plano_conta'),
            "descricao" => env("APP_NAME"),
            "plano" => $classes,
            "contas" => $contas,
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];
    
        return view('dashboard.plano-geral-contas.index', $head);
    }
}
