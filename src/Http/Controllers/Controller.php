<?php

namespace nailfor\shazam\API\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController implements ControllerInterface
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected function error(int $code, string $message): mixed
    {
        return abort($code, json_encode([
            'message' => $message,
        ]));
    }

    /**
     * Aborts execution with code 403 and returns an error.
     *
     * @param string $message сообщение
     */
    protected function accessDenied(string $message = 'You are forbidden to access this'): mixed
    {
        return $this->error(403, $message);
    }

    /**
     * Aborts execution with code 404 and returns an error.
     *
     */
    protected function pageNotFound(string $message = 'Page not found'): mixed
    {
        return $this->error(404, $message);
    }
}
