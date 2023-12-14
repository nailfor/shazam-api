<?php

namespace nailfor\shazam\API\Repositories;

use Illuminate\Http\Request;
use nailfor\shazam\API\Http\Resources\CollectionResource;
use nailfor\shazam\API\Http\Resources\Resource;

abstract class APIRepository extends ModelRepository implements APIRepositoryInterface
{
    protected $collection = CollectionResource::class;

    protected $resource = Resource::class;

    /**
     * Store data to object.
     *
     */
    public function store(Request $request): mixed
    {
        $object = $this->getObject($request);
        $data = $this->getData($request);
        $response = $this->storeObject($object, $data, $request);

        if ($response === true) {
            $response = [
                'id' => $object->id,
            ];
        }

        return $response;
    }

    /**
     * Return models as CollectionResources.
     */
    public function getCollection(Request $request): mixed
    {
        $models = $this->getModels($request);
        $collection = $this->collection;

        return new $collection($models);
    }

    /**
     * Return the Model as Resource.
     */
    public function getResource(mixed $id): mixed
    {
        $object = $this->getObjectById($id);
        $resource = $this->resource;
        if (!$resource) {
            return $object;
        }

        $resource::withoutWrapping();
        $result = new $resource($object);

        return $this->afterGetModels($result);
    }

    /**
     * Remove the Model.
     *
     */
    public function destroy(mixed $id): void
    {
        $object = $this->getObjectById($id);

        $this->beforeDestroy($object);
        $object->delete();
        $this->afterDestroy($id);
    }

    /**
     * Return all data from Request.
     *
     * @param Request $request запрос
     */
    protected function getData(Request $request): array
    {
        return $request->toArray();
    }

    protected function beforeDestroy(mixed $object): void
    {
    }

    protected function afterDestroy(mixed $id): void
    {
    }
}
