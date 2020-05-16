<?php

namespace Tinkeround\Tests\DatabaseMethods;

use Illuminate\Database\Events\QueryExecuted;
use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `logQueryCount()` method.
 */
class LogQueryCountTest extends TestCase
{
    private $dumpCollector;

    /** @var TinkeroundLogQueryCount */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock(TinkeroundLogQueryCount::class);
    }

    function test_it_logs_zero_values_before_first_query()
    {
        $this->testy->logQueryCount();

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 0 → 0ms', $this->dumpCollector->shiftDump());
    }

    function test_it_logs_correct_query_count_and_time()
    {
        $this->dispatchFakeQueryEvent(1);

        $this->testy->logQueryCount();
        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 1 → 1ms', $this->dumpCollector->shiftDump());

        $this->dispatchFakeQueryEvent(10);

        $this->testy->logQueryCount();
        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 2 → 11ms', $this->dumpCollector->shiftDump());
    }

    function test_it_contains_comment()
    {
        $this->testy->logQueryCount('test');
        $this->assertContains('test', $this->dumpCollector->shiftDump());
    }

    /** @noinspection PhpUndefinedFieldInspection */
    private function createFakeQueryEvent(float $time = 0): QueryExecuted
    {
        $fakeQuery = $this->createMock(QueryExecuted::class);

        $fakeQuery->sql = 'fake sql';
        $fakeQuery->bindings = [];
        $fakeQuery->time = $time;

        /** @var QueryExecuted $fakeQuery */
        return $fakeQuery;
    }

    private function dispatchFakeQueryEvent(float $time = 0): void
    {
        $event = $this->createFakeQueryEvent($time);
        $this->testy->handleFakeQueryExecutedEvent($event);
    }
}

abstract class TinkeroundLogQueryCount extends Tinkeround
{
    public function handleFakeQueryExecutedEvent(QueryExecuted $event): void
    {
        $this->handleQueryExecutedEvent($event);
    }
}
