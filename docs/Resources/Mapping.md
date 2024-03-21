# Mapping

For a collection you can specify a mapping model

```php
<?php

namespace App\Http\Resources;

use nailfor\shazam\API\Http\Resources\CollectionResource;

class ShortUserCollectionResource extends CollectionResource
{
    protected function mapping(mixed $item): array
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
        ];
    }
}
```
