<?php

namespace nailfor\shazam\API\Http\Controllers;

use Illuminate\Http\Request;

abstract class AccessController extends ModelController
{
    /**
     * Controller permissions.
     *
     */
    protected array $can = [];

    /**
     * Controller restrictions.
     *
     */
    protected array $cant = [];

    protected static array $route = [];

    protected static $resourceDefaults = [
        'index',
        'store',
        'show',
        'destroy',
    ];

    /**
     * Make routes
     * @return array
     */
    public static function getRoute(): array
    {
        $route = static::$route;
        $only = $route['only'] ?? static::$resourceDefaults;
        $route['only'] = $only;

        return $route;
    }

    /**
     * @inheritDoc
     */
    public function index(Request $request): mixed
    {
        if (in_array('index', $this->cant)) {
            return $this->pageNotFound();
        }

        return parent::index($request);
    }

    /**
     * @inheritDoc
     */
    public function store(Request $request): mixed
    {
        if (!in_array('store', $this->can)) {
            return $this->pageNotFound();
        }

        return parent::store($request);
    }

    /**
     * @inheritDoc
     */
    public function show(Request $request, mixed $id): mixed
    {
        if (in_array('show', $this->cant)) {
            return $this->pageNotFound();
        }

        return parent::show($request, $id);
    }

    /**
     * @inheritDoc
     */
    public function destroy(Request $request, mixed $id): mixed
    {
        if (!in_array('destroy', $this->can)) {
            return $this->pageNotFound();
        }

        return parent::destroy($request, $id);
    }
}
