<?php

namespace App\Http\Middleware;

use App\Models\Configuracao;
use App\Models\ConfiguracaoEmpressora;
use App\Models\ControloSistema;
use App\Models\Entidade;
use App\Models\Modulo;
use App\Models\ModuloEntidade;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Licenca
{
    protected $except = [
        'dashboard-admin',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
                                    
            // Obtém o usuário autenticado com a relação 'empresa'
            $controlo = Entidade::findOrFail(Auth::user()->entidade_id);
            $users = User::findOrFail(Auth::user()->id);
            
            if($controlo->status == "desactivo" && $users->login_access == false) {
                
                $status = "desactivo";
                
                if($controlo->status == "desactivo") {
                    $status = "activo";
                    
                    $users->login_access = true;
                    $users->update();
                }
                
                $controlo->status = $status;
                $controlo->update();
                
                $dataActual = date("Y-m-d");
                
                $configuracao = Configuracao::first();
    
                $verificar = ControloSistema::where("entidade_id", $controlo->id)->first();
    
                $modulo = Modulo::where("modulo", "Gestão Facturação")->where("tipo", "Empresa")->first();
                
                if(!$verificar) {
                    ControloSistema::create([
                        "inicio" => $dataActual,
                        "final" => date("Y-m-d", strtotime($dataActual . "+{$configuracao->limite_dias}days")),
                        "user_id" => $users->id,
                        "entidade_id" => $controlo->id,
                    ]);
                }
                    
                $configuracao = ConfiguracaoEmpressora::where("entidade_id", $controlo->id)->first();
                
                if(!$configuracao) {
                    ConfiguracaoEmpressora::create([
                        "empressao" => false,
                        "funcionamento" => false,
                        "metodo_empressao" => false,
                        "entidade_id" => $controlo->id,
                    ]);
                }
                
                $modulo_entidade = ModuloEntidade::where("entidade_id", $controlo->id)->first();
                
                if(!$modulo_entidade) {
                    ModuloEntidade::create([
                        "entidade_id" => $controlo->id,
                        "modulo_id" => $modulo->id
                    ]);
                }
                
            }
            
            if($controlo->dias_licencas($controlo->id) <= 0){
                return redirect()->route('licenca-activa');
            }

        }

        return $next($request);
    }
}