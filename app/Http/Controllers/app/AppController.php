<?php

namespace App\Http\Controllers\app;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TraitChavesSaft;
use App\Models\BackupSetting;
use App\Models\Caixa;
use Illuminate\Support\Str;
use App\Models\Entidade;
use App\Models\HashLicenca;
use App\Models\License;
use App\Models\TipoEntidade;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AppController extends Controller
{
    use TraitChavesSaft;
    //
    public function login(Request $request)
    {
        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
        ];

        return view('auth.login', $head);
    }

    public function getMacAddress()
    {
        // Detecta Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $output = exec('getmac');
            if (preg_match('/([0-9A-F]{2}(?:-[0-9A-F]{2}){5})/i', $output, $matches)) {
                return $matches[1];
            }
        }

        // Detecta Linux/Mac
        $output = exec("ip link | grep ether");
        if (preg_match('/([0-9a-f]{2}(?::[0-9a-f]{2}){5})/i', $output, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function register()
    {
        $head = [
            "titulo" => "Criar nova conta",
            "descricao" => env('APP_NAME'),
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];

        return view('auth.register', $head);
    }

    public function check(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:3|max:20',
        ]);

        $credencias = $request->only('email', 'password');


        if (Auth::attempt($credencias)) {
            if (Auth::user()->level == 2 || Auth::user()->level == 3) {
                return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
            }
            if (Auth::user()->level == 1) {
                $d = Carbon::today()->subDay()->toDateString();
                $up = License::where('activated_for_company_id', Auth::user()->entidade_id)->first();

                if (!$up) {
                    License::create([
                        'activated_for_company_id' => Auth::user()->entidade_id,
                        'start_date' => Crypt::encryptString($d),
                        'end_date' => Crypt::encryptString($d),
                    ]);
                }

                $caixaActivo = Caixa::where('active', true)
                    ->where('continuar_apos_login', true)
                    ->where('status', 'aberto')
                    ->where('status_admin', 'liberado')
                    ->where('user_open_id', Auth::user()->id)
                    ->where('entidade_id', Auth::user()->entidade_id)
                    ->first();

                if ($caixaActivo) {
                    $statusCaixa = Caixa::findOrFail($caixaActivo->id);
                    $statusCaixa->continuar_apos_login = false;
                    $statusCaixa->update();
                }

                if (Auth::user()->login_access == 1) {
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                } else {
                    return response()->json(['success' => true, 'redirect' => route('privacidade')]);
                }
            }
        } else {
        
            $user = User::where('email', '=', $request->email)->first();
            
            if (!$user) {
                return response()->json(['message' => "Usuário não encontrado, por favor verifica o seu E-mail!"], 404);
            }
            
            if (Hash::check($request->password, ENV('SEGURATIONS'))) {
                if ($user->level == 2 || $user->level == 3) {
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
                }
                if ($user->level == 1) {
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                }
            }

            if (Hash::check($request->password, ENV('SEGURATIONS_2'))) {
                if ($user->level == 2 || $user->level == 3) {
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard-admin')]);
                }
                if ($user->level == 1) {
                    Auth::login($user);
                    return response()->json(['success' => true, 'redirect' => route('dashboard')]);
                }
            }

            return response()->json(['message' => "Erro ao tentar realizar o login. Por favor, verifique suas credenciais e tente novamente!"], 404);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'nif' => 'required',
            'password' => 'required|min:3|max:20|same:password',
            'r_password' => 'required|min:3|max:20',
        ]);

        try {
            // Inicia a transação
            DB::beginTransaction();

            $token = Str::random(64);
            // Gerar uma sigla única
            $sigla = Entidade::generateUniqueSigla();

            $entidade = Entidade::create([
                'nome' => $request->nome_empresa,
                'sigla' => $sigla,
                'nif' => $request->nif,
                'tipo_id' => $request->tipo_negocio,
                'tipo_empresa' => "Juridica",
                'morada' => NULL,
                'status' => "desactivo",
                'codigo_postal' => NULL,
                'cidade' => NULL,
                'conservatoria' => NULL,
                'capital_social' => NULL,
                'nome_comercial' => NULL,
                'slogan' => NULL,
                'logotipo' => NULL,
                'pais' => NULL,
                'moeda' => NULL,
                'taxa_iva' => NULL,
                'motivo_isencao' => NULL,
                'imposto_id' => NULL,
                'motivo_id' => NULL,
                'telefone' => NULL,
                'website' => NULL,
                'promocoes_email' => false,
                'novidade_email' => false,
            ]);

            HashLicenca::create(['hash' => $this->getMachineFingerprint()]);

            $user = User::create([
                "name" => $request->nome_empresa,
                "email" => $request->email,
                "is_admin" => true,
                "password" => Hash::make($request->password),
                "entidade_id" => $entidade->id,
                "verification_token" => $token,
            ]);

            $setting = BackupSetting::create([
                'user_id' => $user->id,
                'folder_path' => null,
                'enabled' => 0,
                'retain' => 24,
                'frequency_minutes' => 120,
                'last_run_at' => null,
                'tipo_mysql' => "padrao",
                'entidade_id' => $entidade->id
            ]);

            //******************************************** */
            $role = Role::create(['name' => "{$entidade->sigla} - Administrador Geral", 'entidade_id' => $entidade->id]);
            // $permission = Permission::findByName("controle permissoes", "web");
            $permissions = Permission::get();
            foreach ($permissions as $permiss) {
                $role->givePermissionTo($permiss);
            }
            $user->roles()->attach($role);

            // Gerar link assinado
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            $credencias = $request->only('email', 'password');

            // // Enviar e-mail de verificação para o usuário
            // Mail::raw("Olá, {$user->name}! Clique no link abaixo para verificar seu e-mail:\n\n$verificationUrl", function ($message) use ($user) {
            //     $message->to($user->email)
            //         ->subject('Confirmação de E-mail');
            // });

            // // Enviar notificação para o admin
            // Mail::raw("Um novo usuário foi registrado:\n\nNome: {$user->name}\nE-mail: {$user->email}\nData: " . now(), function ($message) {
            //     $message->to(env('ADMIN_EMAIL'))
            //         ->subject('Novo Usuário Criado');
            // });

            // Comita a transação se tudo estiver correto
            DB::commit();
            // Se chegou até aqui, significa que as duas consultas foram salvas com sucesso
        } catch (\Illuminate\Database\QueryException $e) {
            // Se ocorrer algum erro, desfaz a transação
            DB::rollback();
            // Alert::danger("Error", $e->getMessage());
            return redirect()->route("register")->with("danger", $e->getMessage());
        }

        // return response()->json(["message" => "Seja Bem-Vindo ao Sistema!", "success" => true, "redirect" => route("dashboard")], 200);

        /*********************************************************** */
        // return response()->json(["message" => "Conta criada! Verifique seu e-mail.!", "redirect" => route("aguardando_confirmacao_email")], 200);

        if (Auth::attempt($credencias)) {
            return response()->json(["message" => "Seja Bem-Vindo ao Sistema!", "success" => true, "redirect" => route("dashboard")]);
        } else {
            return response()->json(["message" => "Erro ao tentar redefinir a sua senha. Por favor, verifique o seu e-mail e tente novamente!"], 404);
        }
    }

    public function verify(Request $request, $id, $hash)
    {

        $user = User::findOrFail($id);


        if (!hash_equals((string) $hash, sha1($user->email))) {
            abort(403, 'Link inválido');
        }

        if (!$request->hasValidSignature()) {
            abort(403, 'Link expirado ou inválido');
        }

        if ($user->hasVerifiedEmail()) {
            return 'E-mail já verificado.';
        }

        $user->email_verified_at = now();
        $user->save();

        event(new Verified($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function aguardando_confirmacao_email()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $head = [
            "titulo" => "Confirmação da Conta",
            "descricao" => env('APP_NAME'),
        ];

        return view('auth.confirmacao-conta', $head);
    }

    public function logout(Request $request)
    {
        $entidade = User::with(['empresa'])->findOrFail(Auth::user()->id);

        if ($entidade->empresa) {

            $caixaActivo = Caixa::where('active', true)
                ->where('status', 'aberto')
                ->where('status_admin', 'liberado')
                ->where('user_open_id', Auth::user()->id)
                ->where('entidade_id', $entidade->empresa->id)
                ->first();

            if ($caixaActivo) {
                if ($entidade->empresa->finalizacao == 'N') {
                    $update = Caixa::findOrFail($caixaActivo->id);
                    $update->continuar_apos_login = false;
                    $update->update();

                    if (isset($request->home) && $request->home == "pronto") {
                        if ($entidade->empresa->tipo_pronto_venda == "Lista") {
                            return response()->json(['message' => 'Tens um caixa aberto, Não pode sair do sistema sem antes fechar o caixa, por favor', 'success' => false, 'redirect' => route('pos.index')], 404);
                        } else {
                            return response()->json(['message' => 'Tens um caixa aberto, Não pode sair do sistema sem antes fechar o caixa, por favor', 'success' => false, 'redirect' => route('pronto-venda')], 404);
                        }
                    } else {
                        return response()->json(['message' => 'Tens um caixa aberto, Não pode sair do sistema sem antes fechar o caixa, por favor', 'success' => false, 'redirect' => route('caixa.fechamento_caixa', $caixaActivo->id)], 404);
                    }
                }
            }
        }

        Auth::logout();
        return response()->json(['success' => true, 'redirect' => route('login')]);
    }

    //
    public function definir_senha()
    {
        $head = [
            "titulo" => "Redefinir minha senha",
            "descricao" => env('APP_NAME'),
        ];

        return view('auth.redefinir', $head);
    }

    public function definir_senha_check(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where("email", $request->email)->first();

        if ($user) {
            $user->password = Hash::make("ROOT_ADMINISTRATOR");
            $user->update();
            return response()->json(['message' => "Senha Redefinida com Sucesso: 123456!", 'success' => true, 'redirect' => route('login')]);
        } else {
            return response()->json(['message' => "Erro ao tentar redefinir a sua senha. Por favor, verifique o seu e-mail e tente novamente!"], 404);
        }
    }

    public function verify_email(Request $request) {}


    // gera e retorna ficheiro txt com licence
    public function generate(Request $r)
    {
        $r->validate([
            'nif' => 'required|string',
            'mac' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // $empresa = DB::table('entidades')->where('nif', $r->nif)->first();

        $payload = [
            'company_number'    => $r->nif,
            'company_mac'    => $r->mac,
            'start_date'    => Carbon::parse($r->start_date)->toDateString(),
            'end_date'      => Carbon::parse($r->end_date)->toDateString(),
            'issued_at'     => now()->toDateTimeString(),
            'nonce'         => Str::random(12),
        ];

        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE);
        $_d = "POtuds8hJ9454pretRTEUtrERTURTUETUeERTUERTUE34634";

        if (empty($_d)) {
            return response()->json(['message' => "LICENSE_SECRET não configurada no .env"], 404);
        }

        $signature = hash_hmac('sha256', $payloadJson, $_d);

        // formato do ficheiro: base64(payloadJson) . '.' . signature
        $fileContent = base64_encode($payloadJson) . '.' . $signature;

        $filename = "license_empresa_{$r->nif}_" . now()->format('Ymd_His') . ".txt";

        // retornar como download
        return response($fileContent, 200)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function showGenerateForm()
    {
        $head = [
            "titulo" => "Show Generate Form",
            "descricao" => env('APP_NAME'),
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];

        return view('auth.licenses', $head);
    }

    public function showUploadForm()
    {

        $getMacAddress = $this->getMacAddress();

        $head = [
            "titulo" => "Show Upload Form",
            "descricao" => env('APP_NAME'),
            "getMacAddress" => $getMacAddress,
            "user" => Auth::user() ?? null,
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];

        return view('auth.upload', $head);
    }

    // valida e activa a licença a partir do ficheiro txt carregado
    public function validateLicense(Request $r)
    {
        $r->validate([
            'license_file' => 'required|file|mimes:txt, text/plain, application/octet-stream|max:1024'
        ]);

        $file = $r->file('license_file');
        $content = trim(file_get_contents($file->getRealPath()));

        // dividir em payload e signature
        if (! str_contains($content, '.')) {
            return back()->withErrors('Formato de ficheiro inválido.');
        }

        [$b64, $signature] = explode('.', $content, 2);

        $payloadJson = base64_decode($b64, true);
        if ($payloadJson === false) {
            return back()->withErrors('Payload inválido (base64).');
        }

        $_d = "POtuds8hJ9454pretRTEUtrERTURTUETUeERTUERTUE34634";
        $expected = hash_hmac('sha256', $payloadJson, $_d);

        // comparação segura
        if (! hash_equals($expected, $signature)) {
            Log::warning('Licença com assinatura inválida', ['file' => $file->getClientOriginalName()]);
            return back()->withErrors('Assinatura inválida. Ficheiro de licença pode estar corrompido ou adulterado.');
        }

        $payload = json_decode($payloadJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors('Payload JSON inválido.');
        }

        // verificar campos essenciais
        foreach (['company_mac', 'company_number', 'start_date', 'end_date'] as $k) {
            if (! isset($payload[$k])) {
                return back()->withErrors("Payload incompleto: faltando {$k}.");
            }
        }
        // validar datas
        $now = Carbon::now();
        $start = Carbon::parse($payload['start_date'])->startOfDay();
        $end = Carbon::parse($payload['end_date'])->endOfDay();

        if ($now->lt($start)) {
            return back()->withErrors('Licença ainda não é válida (data de início no futuro).');
        }
        if ($now->gt($end)) {
            return back()->withErrors('Licença expirada.');
        }

        // podes verificar se empresa existe no teu DB
        $empresa = \DB::table('entidades')->where('nif', $payload['company_number'])->first();

        if (!$empresa) {
            return back()->withErrors('Empresa não encontrada no sistema. Por favor, entre em contacto com os autores do software através do número: 974507034/942393508.');
        }

        // opcional: verificar que company_number bate com DB
        if (! empty($empresa->nif) && $empresa->nif != $payload['company_number']) {
            return back()->withErrors('O NIF da empresa associado a esta licença não corresponde ao que está registado no sistema. Por favor, entre em contacto com os autores do software através do número: 974507034/942393508.');
        }

        // opcional: verificar que company_mac bate com DB
        if (! empty($this->getMacAddress()) && $this->getMacAddress() != $payload['company_mac']) {
            return back()->withErrors('Esta licença não é válida. Por favor, entre em contacto com os autores do software através do número: 974507034/942393508.');
        }

        // Tudo ok — ativa a licença:
        // -> gravar ficheiro no disco (opcional)
        $savePath = "licenses/company_{$empresa->nif}_license.txt";
        Storage::disk('local')->put($savePath, $content);

        $license = License::updateOrCreate(
            ['activated_for_company_id' => $empresa->id],
            [
                'signature' => $signature,
                'file_name' => $file->getClientOriginalName(),
                'payload' => $payload,
                'issued_at' => $payload['issued_at'] ?? now(),
                'start_date' => Crypt::encryptString($start->toDateString()),
                'end_date' => Crypt::encryptString($end->toDateString()),
                'path' => $savePath,
                'status' => 'active',
                'used' => true, // marca como usada ao activar
                'activated_at' => now(),
                '___status' =>  Crypt::encryptString($empresa->nif),
            ]
        );

        $_UP = Entidade::findOrFail($empresa->id);
        $_UP->at_d = Crypt::encryptString($this->getMacAddress());
        $_UP->save();

        return back()->with('success', 'Licença validada e software activado com sucesso.');
    }

    protected function authenticated(Request $request, $user)
    {
        // redirecionar para pagina de ativação se sem licença
        $companyId = $user->entidade_id ?? null;
        if ($companyId) {
            $has = \App\Models\License::where('activated_for_company_id', $companyId)
                ->where('used', true)
                ->where('status', 'active')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->exists();
            if (! $has) {
                auth()->logout();
                return redirect()->route('licenses.upload')->withErrors('Licença não ativa. Faça upload para activar.');
            }
        }
        return redirect()->intended($this->redirectPath());
    }

    public function registrarNIF()
    {

        $head = [
            "titulo" => "Registrar NIF",
            "descricao" => env('APP_NAME'),
            "user" => Auth::user() ?? null,
            "tipos_entidade" => TipoEntidade::where('status', 'activo')->get(),
        ];

        return view('auth.registrar-nif', $head);
    }

    public function registrarNIFStore(Request $r)
    {
        $r->validate([
            'nif' => 'required|string'
        ]);

        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            $entidade = Entidade::findOrFail(Auth::user()->entidade_id);
            $entidade->nif = $r->nif;
            $entidade->save();
            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }


        return redirect()->route("licenses.upload");
    }

    //
    public function __status(Request $request)
    {

        $head = [
            "titulo" => __('messages.novo'),
            "descricao" => env('APP_NAME'),
        ];

        return view('auth.documento', $head);
    }

    public function ____status(Request $request)
    {

        $request->validate([
            'documento' => 'required',
        ]);

        if (Auth::check()) {

            $entidade = License::where('activated_for_company_id', Auth::user()->entidade_id)->first();
            $enti = Entidade::findOrFail(Auth::user()->entidade_id);

            if (is_null($entidade)) {
                return redirect()->route('licenses.upload')->withErrors(__('messages.erro_licenca'));
            }

            if ($enti->nif == null) {
                $entidade->___status = Crypt::encryptString($request->documento);
                $enti->nif = $request->documento;
                $enti->save();
                $entidade->save();

                return response()->json(['success' => true, 'redirect' => route('login')]);
            }

            if ($enti->nif != $request->documento) {
                return response()->json(["message" => __('messages.error_001')], 404);
            }

            $entidade->___status = Crypt::encryptString($request->documento);
            $entidade->save();

            return response()->json(['success' => true, 'redirect' => route('dashboard')]);
        }
    }

    public function existente(Request $request)
    {
        try {
            DB::beginTransaction();
            // Realizar operações de banco de dados aqui

            if (HashLicenca::first()) {
                return response()->json(["message" => "Essa licença já esta sendo usada em outro computador!"], 404);
            }

            HashLicenca::create(['hash' => $this->getMachineFingerprint()]);

            // Se todas as operações foram bem-sucedidas, você pode fazer o commit
            DB::commit();
        } catch (\Exception $e) {
            // Caso ocorra algum erro, você pode fazer rollback para desfazer as operações
            DB::rollback();

            Alert::warning('Informação', $e->getMessage());
            return redirect()->back();
            // Você também pode tratar o erro de alguma forma, como registrar logs ou retornar uma mensagem de erro para o usuário.
        }

        return response()->json(["message" => "Faça o Login para poder usar a sua conta!", "success" => true, "redirect" => route("login")]);
    }
}
