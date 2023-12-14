<?php

namespace nailfor\shazam\API\Factory;

use Illuminate\Http\Request;

class ClassReplacer
{
    /**
     * Replaces the passed class based on request parameters.
     *
     * @param ?string $modelRequest
     */
    public function handle(Request $request, string $defaultClass, ?string $modelRequest, string $suffix): string
    {
        if (!$modelRequest) {
            return $defaultClass;
        }

        $type = $request->get($modelRequest, '');
        if (!$type) {
            return $defaultClass;
        }
        $type = ucfirst($type);
        $model = "$defaultClass\\$type$suffix";

        return $this->replace($defaultClass, $model);
    }

    protected function replace(string $defaultClass, string $model): string
    {
        if (!class_exists($model)) {
            return $defaultClass;
        }

        return $model;
    }
}
