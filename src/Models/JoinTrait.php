<?php

namespace nailfor\shazam\API\Models;

trait JoinTrait
{
    protected array $alreadyJoin = [];

    protected function isJoinExists(string $join)
    {
        $check = $this->alreadyJoin[$join] ?? 0;
        $this->alreadyJoin[$join] = true;

        return $check;
    }
}
