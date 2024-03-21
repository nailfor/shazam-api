## Scopes


```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;

    //these conditions will be met regardless of the request
    protected array $scopes = [
        'active',
    ];

    //The request parameters will be defined here
    //If the request parameter matches the name of the scope, it will be executed.
    //Otherwise, you can specify it as a "parameter" => "scopeName"
    protected function getScopes(): array
    {
        return [
            'addressId',
        ]
    }
}

```

```php
namespace App\Models;

use nailfor\shazam\API\Models\Model;

class User extends Model
{
    public const TABLE_NAME = 'user';

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeAddressId(Builder $query, string $id): Builder
    {
        return $query->where('addressId', $id);
    }
}
```
