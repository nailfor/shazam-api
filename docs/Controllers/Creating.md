## Creating

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
