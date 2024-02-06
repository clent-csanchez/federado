<?php

namespace Clent\Federado;

use Clent\Federado\Http\Middleware\ValidateIncomingSessionToken;
use Illuminate\Support\ServiceProvider;

class FederadoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('validate.incoming.session.token', ValidateIncomingSessionToken::class);

        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        $this->publishes([
            __DIR__ . '/config/federado.php' => config_path('federado.php'),
        ]);
    }
}
