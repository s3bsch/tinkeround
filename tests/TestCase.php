<?php

namespace Tinkeround\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Tinkeround\Tinkeround;

/**
 * Base test case.
 */
class TestCase extends PHPUnitTestCase
{
    /**
     * @return MockObject|Tinkeround
     */
    protected function getTestableTinkeroundInstance()
    {
        return $this->getMockForAbstractClass(Tinkeround::class, [], '', false);
    }
}
