<?php

namespace App\Providers;

use App\Http\Controllers\TraitHelpers;
use App\Models\Loja;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    
    use TraitHelpers;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
    
            $loja = null;
    
            if (Auth::check()) {
                $loja = $this->LOJA_ACTIVA_USER();
            }
    
            $view->with([
                'LOJAACTIVAOPERADOR' => $loja,
            ]);
        });
    }
}
