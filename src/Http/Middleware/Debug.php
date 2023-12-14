<?php

namespace nailfor\shazam\API\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use nailfor\shazam\API\Helpers\Logger;

class Debug
{
    public function handle(Request $request, Closure $next)
    {
        if (config('shazam.debug', false)) {
            DB::connection()->enableQueryLog();
            Logger::$start = microtime(true);
        }

        return $next($request);
    }
}
