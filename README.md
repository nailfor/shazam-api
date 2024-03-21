# Fast API development for Laravel

This library allows API developers to quick create APIs in a minimalist way.

## Features
All models are inherited from Illuminate\Database\Eloquent\Model so most methods work natively

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require nailfor/shazam-api
```

## Configure

Add config/app.php

```php
    'providers' => [
        ...
        nailfor\shazam\API\Providers\RouteServiceProvider::class,
    ];

```

config/shazam.php
```php
<?php

use nailfor\shazam\Http\Controllers\Controller;
use nailfor\shazam\API\Models\Paginator;

return [
    'namespace' => 'App',
    'path' => 'Http/Controllers',
    'routes' => [
        //there is subdirectory of Http/Controllers for routing
        'API',
    ],
    'paginator' => Paginator::class,

    'debug' => env('SQL_DEBUG', false),
];
```

## Usage

### Basic usage

#### Repositories
- Create dir Repositories\API in your app
- Add UserRepository.php like that
```php
<?php

namespace App\Repositories\API;

use App\Models\User;
use nailfor\shazam\API\Repositories\APIRepository;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;
}
```

#### Controllers

- Create subdirectory API in Http/Controllers directory
- Add UserController.php with
```php
<?php

namespace App\Http\Controllers\API;

use App\Repositories\API\UserRepository;
use nailfor\shazam\API\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }
}
```
You can specify rules for your controllers with
```php
class UserController extends ApiController
{
    //By default can't store and destroy
    protected array $can = [
        'store',
    ];

    //By default can index and show
    protected array $cant = [
        'index',
        'show',
    ];
    ...
}
```


#### Request validations

- Create subdirectory in Http/Requests
- Add request file or just run ./artisan make:request StoreRequest

StoreRequest.php 
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Id is require',
        ];
    }
}
```

Now you can edit your controller class like that
```php
class UserController extends ApiController
{
    protected static array $requests = [
        'store' => StoreRequest::class,
    ];
    ...
}
```
When 'store' is one of Laravel CRUD operations: index, show, store and destroy

- Edit app\Http\Kernel.php, find "protected $middlewareGroups = [...]" and add to each
```php
    \nailfor\shazam\API\Http\Middleware\RequestValidator::class,
```

After that you can do get request like http:://localhost/api/user

## Credits

- [nailfor](https://github.com/nailfor)

License
-------

The GNU License (GNU). Please see [License File](LICENSE.md) for more information.
