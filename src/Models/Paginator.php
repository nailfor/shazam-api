<?php

namespace nailfor\shazam\API\Models;

use Illuminate\Pagination\Paginator as EloquentPaginator;

class Paginator extends EloquentPaginator
{
    /**
     * @inheritdoc
     */
    public function toArray()
    {
        return [
            'current_page' => $this->currentPage(),
            'first_page_url' => $this->url(1),
            'prev_page_url' => $this->previousPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
        ];
    }
}
