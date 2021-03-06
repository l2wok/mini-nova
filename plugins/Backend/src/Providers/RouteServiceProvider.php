<?php

namespace Backend\Providers;

use Mini\Routing\Router;
use Mini\Plugins\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the module.
     *
     * @var string|null
     */
    protected $namespace = 'Backend\Controllers';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Mini\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        //
    }

    /**
     * Define the routes for the module.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(array('middleware' => 'web', 'namespace' => $this->namespace), function($router)
        {
            require plugin_path('Backend', 'Routes.php');
        });
    }
}
