<?php

namespace Isofman\LaravelXman;

/**
 * Class ExecutionManager
 * @package Isofman\LaravelXman
 */
class ExecutionManager
{
    /**
     * @param $taskName
     * @return bool
     */
    public function checkin($taskName)
    {
        $key = 'xman.' . $taskName . '.active';

        if (! $exist = app('cache')->has($key)) {
            app('cache')->set($key, true);
        }

        return ! $exist;
    }

    /**
     * @param $taskName
     */
    public function checkout($taskName)
    {
        $key = 'xman.' . $taskName . '.active';
        app('cache')->forget($key);
    }
}
