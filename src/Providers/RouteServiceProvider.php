<?php

namespace nailfor\shazam\API\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Route;
use nailfor\shazam\API\Helpers\FileIterator;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

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

        Route::group($resource, fn () => Route::resources($routes));
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

            $key = $route[0];
            $routes[$key] = "{$this->namespace}\\{$resource}\\{$route[1]}";
        }

        return $routes;
    }

    protected function getRoute(string $resource, string $class): array
    {
        $className = $this->getClassName($class);

        $disabled = $this->routesDisabled[$resource] ?? 0;
        if ($disabled && in_array($className, $disabled)) {
            return [];
        }

        $name = str_replace('Controller', '', $className);
        $route = mb_strtolower("$resource/$name");

        return [$route, $class];
    }

    protected function getClassName(string $class): string
    {
        $pathClass = explode('\\', $class);

        return  end($pathClass);
    }
}
