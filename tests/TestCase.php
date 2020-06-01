<?php

namespace Tinkeround\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tinkeround\Tinkeround;

/**
 * Base test case.
 */
class TestCase extends OrchestraTestCase
{
    /** @var DumpCollector */
    protected $dumpCollector;

    /** @var bool Prevent creation of {@link DumpCollector} on test setup */
    protected $preventDumpCollector = false;

    /** @var Tinkeround */
    protected $testy;

    function setUp(): void
    {
        parent::setUp();

        $this->testy = $this->createTinkeroundMock();

        if (!$this->preventDumpCollector) {
            $this->dumpCollector = DumpCollector::newInstance();
        }
    }

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
