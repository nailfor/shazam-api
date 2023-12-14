<?php

namespace nailfor\shazam\API\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use nailfor\shazam\API\Exceptions\EmptyModelException;

abstract class ObjectRepository implements ObjectRepositoryInterface
{
    protected static string $model;

    /**
     * Get Model by Id.
     *
     * @param mixed $id model id
     * @return mixed The Model
     */
    public function getObjectById(mixed $id): mixed
    {
        $model = static::$model;
        $id = $this->formatId($id);
        if ($id) {
            $object = $this->findById($id);

            return $this->afterFind($object);
        }

        $object = new $model();

        return $this->afterCreate($object);
    }

    /**
     * Get Model.
     *
     * @return mixed The Model
     */
    public function getObject(Request $request): mixed
    {
        $id = $request->get('id');

        return $this->getObjectById($id);
    }

    /**
     * Store request data to Model and save.
     *
     */
    public function storeObject(mixed $object, array $data, Request $request): mixed
    {
        $object->fill($data);

        $this->beforeSave($object, $data);
        $result = $object->save();
        if ($result) {
            $result = $this->afterSave($object, $request);
        }

        return $result;
    }

    protected function findById(mixed $id): mixed
    {
        $model = static::$model;

        return $model::findOrFail($id);
    }

    /**
     * Format for $id.
     *
     *
     */
    protected function formatId(mixed $id): mixed
    {
        return $id;
    }

    /**
     * Return QueryBuilder.
     *
     * @throws EmptyModelException
     */
    protected function getQuery(): Builder
    {
        $class = static::$model;
        if (!$class) {
            throw new EmptyModelException();
        }
        $query = $class::query();

        return $this->afterGetQuery($query);
    }

    protected function afterGetQuery(Builder $query): Builder
    {
        return $query;
    }

    protected function afterFind(mixed $object): mixed
    {
        return $object;
    }

    protected function afterCreate(mixed $object): mixed
    {
        return $object;
    }

    protected function beforeSave(mixed $object, array $data): void
    {
    }

    protected function afterSave(mixed $object, Request $request): mixed
    {
        return true;
    }
}
