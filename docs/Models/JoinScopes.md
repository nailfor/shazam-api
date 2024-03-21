## Creating

For join scopes, there is a trait that checks the connection of the scope to the builder. 
A typical call looks like this

```php

namespace App\Models;

use nailfor\shazam\API\Models\Model;

class User extends Model
{
    public const TABLE_NAME = 'user';

    public function scopeJoinAddress(Builder $query): Builder
    {
        if ($this->isJoinExists('JoinAddress')) {
            return $query;
        }

        return $query->join(...);
    }
}
```
