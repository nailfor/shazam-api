<?php

namespace nailfor\shazam\API\Repositories;

use Illuminate\Http\Request;

interface APIRepositoryInterface extends ObjectRepositoryInterface
{
    /**
     * Return models as CollectionResources.
     * @return resource
     */
    public function getCollection(Request $request): mixed;

    /**
     * Return the Model as Resource.
     */
    public function getResource(mixed $id): mixed;

    /**
     * Store data to object.
     *
     */
    public function store(Request $request): mixed;

    /**
     * Remove the Model.
     *
     */
    public function destroy(mixed $id): void;
}
