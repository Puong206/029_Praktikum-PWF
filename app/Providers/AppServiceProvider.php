<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Dedoc\Scramble\Scramble;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('manage-product', function(User $user) {
            return $user->role === 'admin';
        });

        Gate::define('export-product', function(User $user) {
            return $user->role === 'admin';
        });

        /**
         * Configure Scramble API Documentation
         */
        Scramble::extendOpenApi(function (\OpenApi\Generators\OpenApi $openapi) {
            $openapi->info->title = 'PWF API Documentation';
            $openapi->info->description = 'API Documentation untuk Product dan Category dengan Autentikasi Token';
            $openapi->info->version = '1.0.0';
            $openapi->info->contact = [
                'name' => 'API Support',
                'url' => 'https://example.com/support',
            ];
        });

        Scramble::routes(function ($routes) {
            return $routes
                ->prefix('api')
                ->middleware('api');
        });
    }
}
