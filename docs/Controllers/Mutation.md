## Model mutation

You can specify a special variable in the request that will override the behavior.
For example, the variable "type" can take the value "short", then the response will contain only the id and name.
/api/user?type=short

```php

namespace App\Http\Controllers\API;

use App\Repositories\API\UserRepository;
use nailfor\shazam\API\Http\Controllers\ApiController;

class UserController extends ApiController
{
    //Stores the name of the request variable that determines the name of the repository.
    protected string $modelRequest = 'type';

    public function __construct(UserRepository $model)
    {
        parent::__construct($model);
    }
}
```

```php

namespace App\Repositories\API;

use App\Models\User;

class UserRepository extends APIRepository
{
    protected static string $model = User::class;
}

```

```php

namespace App\Repositories\API\User;

use App\Repositories\API\UserRepository;
use App\Http\Resources\ShortUserCollectionResource;

class ShortRepository extends UserRepository
{
    protected $collection = ShortUserCollectionResource::class;
}

```

Or you can specify the output format as a file extension: /api/user?type=pdfexport
```php

namespace App\Repositories\API\User;

use App\Repositories\API\UserRepository;
use App\Http\Resources\PDFUserCollectionResource;

class PdfExportRepository extends UserRepository
{
    protected $collection = PDFExportUserCollectionResource::class;
}

```
