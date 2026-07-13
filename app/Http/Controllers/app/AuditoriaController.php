<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class AuditoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user->can('controle auditoria')) {
            
            return redirect()->back()->with('error-permissao', "Você não possui permissão para esta operação, por favor, contacte o administrador!");
        }
    
        //
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $logs = Audit::where('entidade_id', $entidade->empresa->id)->latest()->get();
        
        $head = [
            "titulo" => "Auditória",
            "descricao" => env('APP_NAME'),
            "logs" => $logs,
            "permissions" => Permission::pluck('id')->toArray(),
            "empresa_logada" => User::with(['empresa.empresa_modulos', 'empresa.tipo_entidade'])->findOrFail(Auth::user()->id),
        ];

        return view('dashboard.auditorias.index', $head);
    }

}
