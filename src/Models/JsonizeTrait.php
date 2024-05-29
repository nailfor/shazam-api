<?php

namespace nailfor\shazam\API\Models;

trait JsonizeTrait
{
    public function jsonSerialize(): array
    {
        $json = $this->toJson ?? 0;
        if (!$json) {
            return $this->attributes;
        }

        $res = [];
        foreach ($json as $key => $value) {
            $name = is_int($key) ? $value : $key;
            $res[$name] = $this->$value;
        }

        return $res;
    }
}
