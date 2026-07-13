<?php

namespace App\Http\Middleware;

use App\Models\Entidade;
use App\Models\License;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
     
        // Permitir a rota de atualização de NIF sem bloqueio (evita loop)
        if (
            $request->routeIs('login') ||
            $request->routeIs('check') ||
            $request->routeIs('existente') ||
            $request->routeIs('register') ||
            $request->routeIs('licenses.create') ||
            $request->routeIs('licenses.generate') ||
            $request->routeIs('licenses.validate') ||
            $request->routeIs('create') ||
            $request->routeIs('logout') ||
            $request->routeIs('___status') ||
            $request->routeIs('____status') ||
            $request->routeIs('licenses.upload')
        ) {
            return $next($request);
        }

        // Se NÃO estiver logado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $entidade = Entidade::findOrFail($user->entidade_id);

        $license = License::where('activated_for_company_id', $entidade->id)->first();
        if (!$license) {
            return redirect()->route('licenses.upload')
                ->withErrors(__('messages.erro_licenca'));
        }

       
        // Se o campo não existe ou está vazio
        if (empty($license->___status) && empty($entidade->nif)) {
            return redirect()->route('___status')
                ->withErrors('NIF da empresa não foi validado.');
        }


        // Tentar descriptografar com segurança
        try {
            $nifLicenca = Crypt::decryptString($license->___status);
        } catch (\Exception $e) {
            return redirect()->route('___status')
                ->withErrors('Dados da licença inválidos. Revalide o NIF.');
        }

        // Comparar NIF
        if ($nifLicenca !== $entidade->nif) {
            return redirect()->route('___status')
                ->withErrors('O NIF da licença não corresponde ao NIF da empresa.');
        }
        return $next($request);
    }
}
