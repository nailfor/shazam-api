## Creating

Minimal code must connect the model to receive data.

```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;
}

```
