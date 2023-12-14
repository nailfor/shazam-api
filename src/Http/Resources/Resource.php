<?php

namespace nailfor\shazam\API\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return $this->getResponse();
    }

    protected function getResponse(): array
    {
        return [
            'data' => $this->mapping($this->resource),
        ];
    }

    public function mapping(mixed $resource): mixed
    {
        return $resource;
    }
}
