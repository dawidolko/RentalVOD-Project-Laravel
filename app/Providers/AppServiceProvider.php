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
         * Asset URLs are built from APP_URL, not from the request header.
         *
         * In the container nginx listens on port 80 and 8000/8001 are only Docker
         * mappings, so PHP sees "localhost" without a port and `asset()` generated
         * links to http://localhost/css/..., which lead nowhere. The result: the page
         * loaded without any styles.
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
