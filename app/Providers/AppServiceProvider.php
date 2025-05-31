<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $codigo = session('codigo_estudiante');
            $intento = null;

            if ($codigo) {
                $intento = DB::table('intento_censo')
                    ->where('id_censista', $codigo)
                    ->orderByDesc('id')
                    ->first();
            }

            $view->with('intento', $intento);
        });
        Paginator::useBootstrapFive();
    }
}
