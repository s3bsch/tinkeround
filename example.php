<?php

use Tinkeround\Tinkeround;

/**
 * Minimal tinkeround example.
 */
class TinkeroundExample extends Tinkeround
{
    protected function tinker(): void
    {
        dump('hello tinkeround!');
    }
}

TinkeroundExample::letsTinker();
