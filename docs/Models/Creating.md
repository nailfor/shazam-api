## Creating

You can use Eloquent\Models, but the library's functionality slightly expands the model's capabilities

```php

namespace App\Models;

use nailfor\shazam\API\Models\Model;

class User extends Model
{
    public const TABLE_NAME = 'user';
}
```

Why is it a constant and not a protected table?
Because the constant is always available, without creating a model, 
if you need a join you can always call the SomeModel::TABLE_NAME
