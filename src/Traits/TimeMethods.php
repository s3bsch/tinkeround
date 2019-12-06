<?php

namespace Tinkeround\Traits;

/**
 * Trait including methods related to time.
 */
trait TimeMethods
{
    /**
     * @return array Containing both gross and net time like [gross => int, net => int]
     */
    public function getTinkerTimes(): array
    {
        $now = microtime(true);

        $laravelStart = (defined('LARAVEL_START') ? LARAVEL_START : 0);
        $gross = intval(($now - $laravelStart) * 1000);

        $net = intval(($now - $this->tinkeroundStart) * 1000);

        return compact('gross', 'net');
    }
}
