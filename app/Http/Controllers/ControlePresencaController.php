<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\Funcionario;
use App\Models\MarcacaoFalta;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ControlePresencaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function index(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
 
        $mesSelecionado = $request->input('mes', now()->format('Y-m'));
        
        [$ano, $mes] = explode('-', $mesSelecionado);
        $inicioMes = Carbon::createFromDate($ano, $mes, 1);
        $fimMes = $inicioMes->copy()->endOfMonth();
        
        $diasDoMes = [];
        for ($date = $inicioMes->copy(); $date->lte($fimMes); $date->addDay()) {
            $diasDoMes[] = $date->copy();
        }
        
        $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
            ->pluck('funcionario_id');
        
        $funcionarios = Funcionario::whereIn("id", $contratos)->with(['faltas', 'ferias'])->where('entidade_id', $entidade->empresa->id)->get();
        
        $head = [
            "titulo" => "Controle de Presenças",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "funcionarios" => $funcionarios,
            "diasDoMes" => $diasDoMes,
            "empresa_logada" => User::with(
                ['empresa.empresa_modulos', 'empresa.tipo_entidade']
            )
            ->findOrFail(Auth::user()->id),
        ];
                 
        return view('dashboard.controle-presencas.index', $head);
    }
   
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
         
        $contratos = Contrato::where('entidade_id', $entidade->empresa->id)
        ->pluck('funcionario_id');
    
        $funcionarios = Funcionario::whereIn("id", $contratos)->with(['faltas', 'ferias'])->where('entidade_id', $entidade->empresa->id)->get();
         
        $head = [
            "titulo" => "Controle de Presenças",
            "descricao" => env('APP_NAME'),
            "entidade" => $entidade,
            "funcionarios" => $funcionarios,
            "empresa_logada" => User::with(
                ['empresa.empresa_modulos', 'empresa.tipo_entidade']
        )
            ->findOrFail(Auth::user()->id),
        ];
                  
        return view('dashboard.controle-presencas.create', $head);
    }
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'funcionario_id' => 'required|array', // ['required', 'array', 'min:1'],
            'date_at' => 'required',
            'status' => 'required',
        ]);
        
     
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);
                
        try {
            DB::beginTransaction();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            
            foreach ($request->funcionario_id as $item) {
          
                $verificar = MarcacaoFalta::where('funcionario_id', $item)
                    ->whereDate('data_registro', $request->date_at)
                    ->where('entidade_id', $entidade->empresa->id)
                ->first();
               
                if(!$verificar){
                    MarcacaoFalta::create([
                        'data_registro' => $request->date_at,
                        'funcionario_id' => $item,
                        'falta_id' => "default",
                        'duracao' => "8",
                        'status' => $request->status,
                        'user_id' => Auth::user()->id,
                        'entidade_id' => $entidade->empresa->id,
                    ]);
                }
            }
                       
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(['success' => true, 'message' => "Dados Salvos com sucesso!"]);

    }
    
}
