<?php

namespace Tinkeround\Tests\LogMethods;

use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `logCount()` method.
 */
class LogCountTest extends TestCase
{
    private $dumpCollector;

    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock();
    }

    function test_it_counts_empty_array()
    {
        $this->testy->logCount([]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Count: 0", $this->dumpCollector->shiftDump());
    }

    function test_it_counts_array_containing_items()
    {
        $this->testy->logCount([1, 2, 3]);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Count: 3", $this->dumpCollector->shiftDump());
    }

    function test_it_counts_empty_collection()
    {
        $this->testy->logCount(collect());

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Count: 0", $this->dumpCollector->shiftDump());
    }

    function test_it_counts_collection_containing_items()
    {
        $list = collect([1, 2, 3]);
        $this->testy->logCount($list);

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals("Count: 3", $this->dumpCollector->shiftDump());
    }
}
