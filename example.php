<?php

use Tinkeround\Tinkeround;

/**
 * Minimal tinkeround example.
 */
class TinkeroundExample extends Tinkeround
{
    protected function tinker(): void
    {
        $this->log('hello tinkeround!');
    }
}

TinkeroundExample::letsTinker();
