<?php

namespace nailfor\shazam\API\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use nailfor\shazam\API\Helpers\FileIterator;

class RouteServiceProvider extends ServiceProvider
{
    protected array $routesDisabled = [];

    protected string $path = '';

    protected string $namepsace = '';

    protected array $resources = [];

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->routesAreCached()) {
            return;
        }

        /** @var UrlGenerator $url */
        $url = $this->app['url'];
        $url->forceRootUrl(config('app.url'));
        $this->resources = config('shazam.routes');

        $path = config('shazam.path');
        $this->path = app_path($path);
        $this->namespace = config('shazam.namespace') . '\\' . str_replace('/', '\\', $path);


        $this->mapRoutes();
        $this->mapWebRoutes();
    }

    protected function mapRoutes(): void
    {
        foreach ($this->resources as $resource) {
            $this->loadRoute(
                $resource,
                [strtolower($resource)]
            );
        }
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $web = config('shazam.controller');
        if (!$web) {
            return;
        }

        $data = [
            '/' => $web,
        ];
        Route::group([
                'middleware' => 'web',
                'as' => '/',
            ], Route::resources($data));
    }

    protected function loadRoute(string $resource, array $middleware): void
    {
        $routes = $this->getRoutes($resource);
        if (!$routes) {
            return;
        }

        $list = explode('/', $resource);
        $as = mb_strtolower(end($list));
        $as .= $as ? '.' : null;
        $resource = [
            'middleware' => $middleware,
            'as' => $as,
        ];

        Route::group($resource, fn (Router $router) => $this->registrar($routes, $router));
    }

    protected function registrar(array $routes, Router $router): void
    {
        foreach ($routes as $name => $controller) {
            $options = [];
            if (method_exists($controller, 'getRoute')) {
                $options = $controller::getRoute();
                $params = $options['parameters'] ?? [];
                if ($params)
                {
                    $keys = array_keys($params);
                    $base = explode('/', $name);
                    $base = end($base);
                    $options['parameters'][$base] = implode('}/{', $keys);
                    $options['wheres'] = $params;
                }
            }
            $router->resource($name, $controller, $options);
        }
    }

    protected function getRoutes(string $resource): array
    {
        $iteratorPath = "{$this->path}/{$resource}";
        if (!file_exists($iteratorPath)) {
            return [];
        }

        $iterator = new FileIterator($iteratorPath);
        $routes = [];
        foreach ($iterator->handle() as $class) {
            $className = $this->getClassName($class);
            $path = "{$resource}/{$className}";
            $dir = "{$this->path}/{$path}";
            if (is_dir($dir)) {
                $subRoutes = $this->getRoutes($path);
                $routes = array_merge($routes, $subRoutes);
                continue;
            }

            $route = $this->getRoute($resource, $class);
            if (!$route) {
                continue;
            }

            $this->pushRoute($resource, $route, $routes);
        }

        return $routes;
    }

    protected function pushRoute(string $resource, array $route, array &$routes): void
    {
        $name = $route[0];
        $key = mb_strtolower("$resource/$name");

        $resource = str_replace('/', '\\', $resource);
        $routes[$key] = "{$this->namespace}\\{$resource}\\{$route[1]}";
    }

    protected function getRoute(string $resource, string $class): array
    {
        $className = $this->getClassName($class);

        $disabled = $this->routesDisabled[$resource] ?? 0;
        if ($disabled && in_array($className, $disabled)) {
            return [];
        }

        $name = str_replace('Controller', '', $className);

        return [$name, $class];
    }

    protected function getClassName(string $class): string
    {
        $pathClass = explode('\\', $class);

        return  end($pathClass);
    }
}
