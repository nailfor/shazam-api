## Configuration

You must add middleware Debug in Kernel.php
```php
protected $middleware = [
    ///...
    \nailfor\shazam\API\Http\Middleware\Debug::class,
];
```
and than enable SQL_DEBUG in your .env
SQL_DEBUG=true

## Repository

For debugging SQL requests you can use DebugCollectionResource
```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;

    protected $collection = DebugCollectionResource::class;
}
```

or inherit from DebugRepository
```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends DebugRepository
{
    protected static string $model = User::class;
}
```

## Response

And after that you can see 'sqls' section in your responses.
```
 {
    "data": [...],
    "sqls": []
 }
```

## Request

To debug the final sql query, you can use ?debug=true param in request url
