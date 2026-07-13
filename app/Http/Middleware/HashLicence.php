<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TraitChavesSaft;
use App\Models\HashLicenca;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HashLicence
{
    use TraitChavesSaft;
    
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
        // Libera login e register SEM autenticação
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

        // Agora sim verifica login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $hash = HashLicenca::first();

        if (!$hash) {
            return redirect()->route('register');
        }   

        if($hash->hash != $this->getMachineFingerprint()) {
            return redirect()->route('register');
        }

        return $next($request);
    }
}
