## Creating

Each model can have a special array that determines how it will be presented in the final json collection

```php

namespace App\Models;

use nailfor\shazam\API\Models\Model;

class User extends Model
{
    public const TABLE_NAME = 'user';

    //json_key => model_value
    protected array $toJson = [
        'id',
        'email',
        'name',
        'fullname' => 'FirsNameWithLast',
        'address',
    ];

    public function getFirsNameWithLastAttribute(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getNameAttribute(): string
    {
        return $this->firstName;
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }
}
```
