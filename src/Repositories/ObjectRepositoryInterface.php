<?php

namespace nailfor\shazam\API\Repositories;

use Illuminate\Http\Request;

interface ObjectRepositoryInterface
{
    public function getObjectById($id);

    public function getObject(Request $request): mixed;

    public function storeObject(mixed $object, array $data, Request $request): mixed;
}
