## Request validation

```php

namespace App\Http\Controllers\API;

use App\Http\Requests\IndexUserRequest;
use App\Http\Requests\ShowUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\DestroyUserRequest;
use App\Repositories\API\UserRepository;
use nailfor\shazam\API\Http\Controllers\ApiController;

class UserController extends ApiController
{
    //validate http requests
    protected static array $requests = [
        'index' => IndexUserRequest::class,
        'show' => ShowUserRequest::class,
        'store' => StoreUserRequest::class,
        'destroy' => DestroyUserRequest::class,
    ];

    protected array $can = [
        'store',
        'destroy',
    ];

    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }
}
```

Alternatively, if your requests inherit from the Illuminate\Http\Request class you can change the method index, save, show, or destroy.
```php

namespace App\Http\Controllers\API;

use App\Http\Requests\IndexUserRequest;
use App\Repositories\API\UserRepository;
use nailfor\shazam\API\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }

    public function index(IndexUserRequest $request): mixed
    {
        return parent::index($request);
    }
}
```
