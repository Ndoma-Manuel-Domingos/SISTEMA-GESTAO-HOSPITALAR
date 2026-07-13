<?php

namespace App\Http\Controllers;

use App\Models\ControloSistema;
use App\Models\Entidade;
use App\Models\HashLicenca;
use App\Models\Pin;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class SegurancaController extends Controller
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

    public function licenca()
    {
        $head = [
            "titulo" => "Activação de Licença",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.licenca', $head);
    }

    public function licenca_post(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $hash = HashLicenca::where('hash', $request->codigo)->first();

            if (!$hash) {
                $dados = (array) Crypt::decrypt($request->codigo);

                $controle = ControloSistema::where('entidade_id', Auth::user()->entidade_id)->first();

                if ($controle) {
                    $controle_update = ControloSistema::findOrFail($controle->id);

                    $controle_update->inicio = $dados['data_inicio'];
                    $controle_update->final = $dados['data_final'];
                    $controle_update->status = "activo";

                    $controle_update->update();

                    HashLicenca::create([
                        'hash' => $request->codigo
                    ]);
                } else {
                    return redirect()->back()->with('danger', 'Ocorreu um erro ao actualizar a sua licença entra em contacto com o administrador do sistema!');
                }
            } else {
                return redirect()->back()->with('danger', 'Codigo Inválido, entra em contacto com o administrador do sistema!');
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->route('dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function renovar_licenca()
    {
        $head = [
            "titulo" => "Renovação de Licença",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.renovar-licenca', $head);
    }

    public function renovar_licenca_post(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $hash = HashLicenca::where('hash', $request->codigo)->first();

            if (!$hash) {
                $dados = (array) Crypt::decrypt($request->codigo);

                $controle = ControloSistema::where('entidade_id', Auth::user()->entidade_id)->first();

                if ($controle) {
                    $controle_update = ControloSistema::findOrFail($controle->id);

                    // 1. Criar objetos DateTime com base nas datas de entrada
                    $date1 = new DateTime($dados['data_inicio']);
                    $date2 = new DateTime($dados['data_final']);

                    // 2. Calcular diferença de dias entre as datas
                    $diff = $date1->diff($date2);
                    $diasRestantes = $diff->format('%a'); // número total de dias

                    // 3. Acrescentar 10 dias à data "final" do banco
                    $novaDataFinal = new DateTime($controle_update->final);
                    $novaDataFinal->modify("+{$diasRestantes} days");

                    $controle_update->final = $novaDataFinal->format('Y-m-d');
                    $controle_update->status = "activo";

                    $controle_update->update();

                    HashLicenca::create([
                        'hash' => $request->codigo
                    ]);
                } else {
                    return redirect()->back()->with('danger', 'Ocorreu um erro ao actualizar a sua licença entra em contacto com o administrador do sistema!');
                }
            } else {
                return redirect()->back()->with('danger', 'Codigo Inválido, entra em contacto com o administrador do sistema!');
            }


            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return redirect()->route('dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function pin()
    {
        $head = [
            "titulo" => "Congelador de tela",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.congelamento', $head);
    }


    public function pin_post(Request $request)
    {
        $request->validate([
            'codigo' => 'required'
        ]);

        if (Auth::user()->codigo == $request->codigo) {

            $user = auth()->user();

            $usuario = User::findOrFail($user->id);
            $usuario->codigo = NULL;
            $usuario->update();

            return redirect()->route('dashboard');
        }

        return redirect()->back();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function create()
    {
        $head = [
            "titulo" => "Congelador de tela",
            "descricao" => env('APP_NAME'),
        ];

        return view('dashboard.pins.create-congelamento', $head);
    }


    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|numeric'
        ], [
            'codigo.required' => 'Informe o codigo por favor',
            'codigo.numeric' => 'O Código deve ser Numerico',
        ]);

        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        $pins = Pin::where('status', 'activo')->where('entidade_id', $entidade->empresa->id)->first();

        if ($pins) {
            $pim = Pin::findOrFail($pins->id);
            $pim->status = 'activo';
            $pim->update();

            return redirect()->route('dashboard');
        }

        $user = auth()->user();


        $usuario = User::findOrFail($user->id);
        $usuario->codigo = $request->codigo;
        $usuario->update();

        return redirect()->route('dashboard');
    }
}
