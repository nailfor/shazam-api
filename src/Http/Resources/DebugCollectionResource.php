<?php

namespace nailfor\shazam\API\Http\Resources;

use Illuminate\Support\Facades\DB;

class DebugCollectionResource extends CollectionResource
{
    /**
     * @inheritDoc
     */
    protected function extend(array $result): array
    {
        if (config('shazam.debug', false)) {
            $result['sql'] = DB::getQueryLog();
        }

        return parent::extend($result);
    }
}
