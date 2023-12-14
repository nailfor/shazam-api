<?php

namespace nailfor\shazam\API\Http\Resources;

use Closure;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class CollectionResource extends ResourceCollection
{
    /**
     * @inheritDoc
     */
    public function toArray($request)
    {
        $result = [
            'data' => $this->getData(),
        ];

        return $this->extend($result);
    }

    protected function getData(): Collection
    {
        if (!method_exists($this, 'mapping')) {
            return $this->collection;
        }

        $closure = Closure::fromCallable([$this, 'mapping']);

        return $this->collection->map($closure);
    }

    protected function extend(array $result): array
    {
        return $result;
    }
}
