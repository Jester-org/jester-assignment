<?php
namespace App\Http;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
class Kernel extends HttpKernel
{
    protected $middleware = [
        // Other middleware...
    ];
    protected $middlewareGroups = [
        'web' => [
            // Other web middleware...
        ],
        'api' => [
            // Other api middleware...
        ],
    ];
    protected $routeMiddleware = [
        // Other route middleware...
    ];
}

