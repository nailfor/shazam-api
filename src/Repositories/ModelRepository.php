<?php

namespace nailfor\shazam\API\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class ModelRepository extends ObjectRepository
{
    protected int   $perPage = 100;

    protected bool  $withPaginate = true;

    protected array $scopes = [];

    protected array $with = [];

    /**
     * Gets the included request parameters and their scope.
     *
     */
    protected function getScopes(): array
    {
        return [
            //'request_param' => 'scope',
            //or
            //'scope' (if request_param equal with scope)
        ];
    }

    /**
     * Set scopes to Query.
     *
     */
    protected function setScope(Builder $query, string $scope, mixed $param): Builder
    {
        return $query->$scope($param);
    }

    protected function getDefaultScope(): string
    {
        return '';
    }

    /**
     * Get Models collection.
     *
     */
    protected function getModels(Request $request): mixed
    {
        $query = $this->getQuery();

        $query = $this->applyScopes($request, $query);
        $query = $this->applyConstScopes($query);

        if ($this->with) {
            $query->with($this->with);
        }

        $result = $this->getPaginate($query, $request);

        return $this->afterGetModels($result);
    }

    protected function getPaginate(Builder $query, Request $request): mixed
    {
        $perPage = config('shazam.pages.perPage', 'per_page');
        $name = config('shazam.pages.page', 'page');
        $perPage = (int) $request->get($perPage, $this->perPage);

        if ($this->withPaginate) {
            return $query->paginate($perPage, ['*'], $name);
        }

        // Starting page == 1
        $page = (int) $request->get($name, 0);
        $page = $page > 0 ? $page : 1;

        return $query
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
    }

    /**
     * Apply scopes to Query.
     *
     */
    protected function applyScopes(Request $request, Builder $query): Builder
    {
        $scopes = $this->getScopes();
        $defaultScope = $this->getDefaultScope();
        foreach ($scopes as $key => $scope) {
            if (is_numeric($key)) {
                $key = $scope;
            }
            $hasParam = $request->has($key);

            if ($hasParam) {
                $defaultScope = '';
                $param = $request->input($key);
                $query = $this->setScope($query, $scope, $param);
            }
        }
        if ($defaultScope) {
            $query = $this->setScope($query, $defaultScope, $request->get($defaultScope, null));
        }

        return $query;
    }

    /**
     * Apply static scope from $this->scopes.
     *
     */
    protected function applyConstScopes(Builder $query): Builder
    {
        foreach ($this->scopes as $scope) {
            $query->$scope();
        }

        return $query;
    }

    protected function afterGetModels(mixed $result): mixed
    {
        return $result;
    }
}
