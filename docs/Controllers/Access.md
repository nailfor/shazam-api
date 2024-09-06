## Access

You can specify access for your controllers with
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

    //you can using all 'options' such as 'wheres', 'trashed' and so on. See options in ResourceRegistrar
    //but also there are short record
    //for example this make a route show with path 'api/user/{name}/{id}'
    protected static array $route = [
        'parameters' => [
            'name' => '[a-z]+',
            'id' => '[0-9]+',
        ],
        'only' => [
            'show',
        ],
    ];

    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }
}
```
