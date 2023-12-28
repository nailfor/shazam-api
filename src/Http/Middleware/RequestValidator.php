<?php

namespace nailfor\shazam\API\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class RequestValidator extends Middleware
{
    /**
     * @inheritdoc
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->validate($request);

        return $next($request);
    }

    /**
     * Create the Request and do validate
     */
    public function validate(Request $request): void
    {
        $route = $request->route();
        $controller = $route->controller;
        if (!$controller || !method_exists($controller, 'getRequests')) {
            return;
        }
        
        $name = $route->getName();
        $res = explode('.', $name);
        $type = end($res) ?? $name;

        $requestClass = $controller->getRequests($type);
        if ($requestClass) {
            //Validation
            App::make($requestClass);
        }
    }

}
