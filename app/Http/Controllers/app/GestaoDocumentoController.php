<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\DepartamentoPasta;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class GestaoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $user = auth()->user();
        
        if (!$user->can('controle documentos')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
        
        
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $documentos = DepartamentoPasta::where('entidade_id', $entidade->entidade_id)->get();
        
        $head = [
            "titulo" => "Perfis",
            "descricao" => env('APP_NAME'),
            "documentos" => $documentos,
            "permissions" => Permission::pluck('id')->toArray(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.gestao-documentos.index', $head);
    }

}
