<?php

namespace Tinkeround\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tinkeround\Tinkeround;

/**
 * Base test case.
 */
class TestCase extends OrchestraTestCase
{
    /**
     * Create testable Tinkeround mock.
     *
     * @param string $className (optional) Tinkeround class name which should be mocked
     * @return \PHPUnit_Framework_MockObject_MockObject|Tinkeround
     */
    protected function createTinkeroundMock(string $className = null)
    {
        $className = $className ?? Tinkeround::class;
        return $this->getMockForAbstractClass($className, [], '', false);
    }
}
