## Eager loading



```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;

    protected array $with = [
        'metro.station',
        'address',
        'comments',
    ];
}

```
