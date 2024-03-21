## Rules

You can specify rules for your controllers with
```php

namespace App\Http\Controllers\API;

use App\Repositories\API\UserRepository;
use nailfor\shazam\API\Http\Controllers\ApiController;

class UserController extends ApiController
{
    //By default can't store and destroy
    protected array $can = [
        'store',
        'destroy',
    ];

    //By default can index and show
    protected array $cant = [
        'index',
        'show',
    ];

    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }
}
```
