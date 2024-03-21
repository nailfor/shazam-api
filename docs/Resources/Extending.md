# Extending

If you need to expand the response with additional fields, you can do like this:

```php
<?php

namespace App\Http\Resources;

use nailfor\shazam\API\Http\Resources\CollectionResource;

class ShortUserCollectionResource extends CollectionResource
{
    protected function extend(array $result): array
    {
        $result['sorting'] = 'default';

        return $result;
    }
}
```
