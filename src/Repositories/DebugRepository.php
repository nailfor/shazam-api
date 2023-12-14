<?php

namespace nailfor\shazam\API\Repositories;

use Doctrine\SqlFormatter\SqlFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use nailfor\shazam\API\Http\Resources\DebugCollectionResource;

abstract class DebugRepository extends APIRepository
{
    protected $collection = DebugCollectionResource::class;

    protected array $keys = [
        'select',
        'from',
        'union',
        'inner',
        'left',
        'where',
        'and ',
        'or ',
        'order',
        'group',
    ];

    /**
     * @inheritDoc
     */
    protected function getPaginate(Builder $query, Request $request): mixed
    {
        $sqlDebug = config('shazam.debug');
        if (!$sqlDebug || !$request->get('debug')) {
            return parent::getPaginate($query, $request);
        }

        $info = $this->getDebugInfo($query);
        $addSlashes = str_replace('%', '', $info);
        $addSlashes = str_replace('?', '%s', $addSlashes);
        $sql = vsprintf($addSlashes, $query->getBindings());

        $formatter = new SqlFormatter();
        $sql = $formatter->highlight($sql);

        foreach ($this->keys as $key) {
            $sql = str_replace($key, "<br>$key", $sql);
        }

        echo $sql;
        exit();
    }

    protected function getDebugInfo(Builder $query): string
    {
        return $query->toSql();
    }
}
