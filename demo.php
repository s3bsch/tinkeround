<?php

use Tinkeround\Tinkeround;

/**
 * Extensive tinkeround demo.
 */
class TinkeroundDemo extends Tinkeround
{
    protected function tinker(): void
    {
        $this->log('hello tinkeround demo!');

        $this->tinkerWithLogMethod();
    }

    /**
     * Template for tinker methods.
     */
    private function tinkerWithTemplate()
    {
        $this->log(__FUNCTION__ . '()…');

        // Here you go!
    }

    private function tinkerWithLogMethod()
    {
        $this->log(__FUNCTION__ . '()…');

        $this->log('');  // → ""
        $this->log(' first message is right trimmed ');  // → " message is trimmed"
        $this->log('more', 'than', 'one', ' message ');  // → "more than one message"

        $this->log(null);  // → `null`
        $this->log('null:', null);  // → "null: `null`"

        $this->log(true);  // → `true`
        $this->log('boolean:', false);  // → "boolean: `false`"

        $this->log(0);  // → `0`
        $this->log('integer:', 42);  // → "integer: `42`"

        $this->log([]);  // → `[]`
        $this->log('some arrays:', [], [1, 2, 'three']);  // → "some arrays: `[]` `[1,2,"three"]`"

        $this->log($this);  // → "`TinkeroundDemo`"
    }
}

TinkeroundDemo::letsTinker();
