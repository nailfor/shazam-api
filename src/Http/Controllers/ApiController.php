<?php

namespace nailfor\shazam\API\Http\Controllers;

use Illuminate\Http\Request;

/**
 *
 * This class should be parent class for other API controllers
 * Class ApiController.
 */
abstract class ApiController extends AccessController
{
    /**
     * @inheritDoc
     */
    public function index(Request $request): mixed
    {
        //for show method you can used /{id} or ?id={id}
        $id = $request->get('id');
        if ($id) {
            return $this->show($request, $id);
        }

        return parent::index($request);
    }
}
