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
     * Create testable Tinkeround mock.
     *
     * @param string $className (optional) Tinkeround class name which should be mocked
     * @return MockObject|Tinkeround
     */
    protected function createTinkeroundMock(string $className = null)
    {
        $className = $className ?? Tinkeround::class;
        return $this->getMockForAbstractClass($className, [], '', false);
    }
}
