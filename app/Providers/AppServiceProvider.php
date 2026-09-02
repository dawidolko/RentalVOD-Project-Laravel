<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
         * Adresy assetow budujemy z APP_URL, nie z naglowka zadania.
         *
         * W kontenerze nginx slucha na porcie 80, a 8000/8001 to dopiero
         * mapowanie Dockera — PHP widzi wiec "localhost" bez portu i `asset()`
         * generowal linki do http://localhost/css/..., czyli w pustke. Efekt:
         * strona ladowala sie bez zadnych styli.
         */
        if ($url = config('app.url')) {
            URL::forceRootUrl($url);

            if (str_starts_with($url, 'https://')) {
                URL::forceScheme('https');
            }
        }
        $this->app->afterResolving(EncryptCookies::class, function ($middleware) {
            $middleware->disableFor('laravel_session');
            $middleware->disableFor('XSRF-TOKEN');
        });

        Gate::define('is-admin', function ($user) {
            return $user->role_id == 1;
        });

        view()->composer('*', function ($view) {
            $view->with('categories', Category::all());
        });
    }
}
