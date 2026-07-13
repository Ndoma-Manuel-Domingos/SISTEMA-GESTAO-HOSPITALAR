<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Cliente;
use App\Models\Fornecedore;
use App\Models\LojaProduto;
use App\Models\OperacaoFinanceiro;
use App\Models\Pin;
use App\Models\Produto;
use App\Models\Receita;
use App\Models\Ticket;
use App\Models\TipoPagamento;
use App\Models\User;
use App\Models\UserLoja;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{

    // Gera uma nova senha (usa transação para garantir número único incremental)
    public function store(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
        
        $serviceId = $request->service_id ?? null;
        
        return DB::transaction(function () use ($entidade, $serviceId, $request){
            
              // se estiveres usando service_id isola contagem por service
            $query = Ticket::query();
            
            if ($serviceId) $query->where('service_id', $serviceId);
        
            // pega maior number existente e soma 1 (ou 1 se vazio)
            $max = $query->max('number') ?? 0;
            $number = $max + 1;
            
            $ticket = Ticket::create([
                'number' => $number,
                'status' => 'waiting',
                'user_id' => Auth::user()->id,
                'service_id' => $serviceId,
                'entidade_id' => $entidade->empresa->id,
                'prefix' => $serviceId ? (optional(\App\Models\Produto::find($serviceId))->codigo_barra ?? null) : null,
            ]);
            
                 // se o pedido for normal (form submit), redireciona com flash
            if ($request->wantsJson()) {
                return response()->json(['ticket' => [
                    'id' => $ticket->id,
                    'number' => $ticket->number,
                    'service' => $ticket->service->nome,
                    'display' => $ticket->displayNumber(),
                ]], 201);
            }
            
            return redirect()->back()->with('ticket_generated', $ticket->displayNumber());
            
        });
    }

    // Operador: chamar o próximo (marca o próximo waiting como called)
    public function callNext(Request $request)
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
                
        return DB::transaction(function () use ($entidade){
            $next = Ticket::where('status', 'waiting')
                ->where('entidade_id', $entidade->empresa->id)
                ->orderBy('number')
            ->first();
            
            if (! $next) {
                return response()->json(['message' => 'Nenhuma senha em espera'], 404);
            }
            
            $next->status = 'called';
            $next->called_at = Carbon::now();
            $next->save();
            
            // Retornar dados para front-end e para os clientes anunciarem
            return response()->json(['ticket' => $next]);
        });
    }
 
 
 
    // Última senha chamada (para polling)
    public function latestCalled()
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);

        $last = Ticket::where('status', 'called')
            ->orderBy('called_at', 'desc')
            ->where('entidade_id', $entidade->empresa->id)
        ->first();
        
        if (!$last) {
            return response()->json(['ticket' => null]);
        }
        return response()->json(['ticket' => $last]);
    }
    
    
    // Opcional: número de senhas pendentes
    public function pendingCount()
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
               
        $count = Ticket::where('status','waiting')
            ->where('entidade_id', $entidade->empresa->id)
        ->count();
        
        return response()->json(['pending' => $count]);
    }
    
    
    public function gerar_senha()
    {
        $entidade = User::with(["empresa"])->findOrFail(Auth::user()->id);
               
        $servicos = Produto::orderBy('nome')->where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Gerar Senhas",
            "descricao" => env("APP_NAME"),
            "servicos" => $servicos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.tickets.index', $head);
    }
    
    public function show_display()
    {
        $servicos = Produto::orderBy('nome')->get();
        
        $head = [
            "titulo" => "Gerar Senhas",
            "descricao" => env("APP_NAME"),
            "servicos" => $servicos,
            "empresa_logada" => User::with(["empresa.empresa_modulos", "empresa.tipo_entidade"])->findOrFail(Auth::user()->id),
        ];
        
        return view('dashboard.tickets.index', $head);
    }
    
}
