<?php

namespace nailfor\shazam\API\Models;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder extends EloquentBuilder
{
    /**
     * @inheritdoc
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        $class = config('shazam.paginator');

        return Container::getInstance()->makeWith($class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
