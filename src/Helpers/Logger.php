<?php

namespace nailfor\shazam\API\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Logger
{
    public static int $start = 0;

    public function __invoke()
    {
        $debug = config('shazam.debug');
        if (!$debug) {
            return;
        }

        $request = request();

        $channel = config('shazam.channel');
        $log = Log::channel($channel);

        $log->info(
            json_encode([
                'user' => Auth::id(),
                'ip' => $request->ip(),
                'action' => $request->path(),
                'data' => $request->toArray(),
                'time' => microtime(true) - static::$start,
            ])
        );
    }
}
