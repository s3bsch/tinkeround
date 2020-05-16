<?php

namespace Tinkeround\Tests\DatabaseMethods;

use Illuminate\Database\Events\QueryExecuted;
use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `logAndResetQueryCount()` method.
 */
class LogAndResetQueryCountTest extends TestCase
{
    private $dumpCollector;

    /** @var TinkeroundLogAndResetQueryCount */
    private $testy;

    function setUp(): void
    {
        $this->dumpCollector = new DumpCollector();
        $this->testy = $this->createTinkeroundMock(TinkeroundLogAndResetQueryCount::class);
    }

    function test_it_logs_zero_values_before_first_query()
    {
        $this->testy->logAndResetQueryCount();

        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 0 → 0ms', $this->dumpCollector->shiftDump());
    }

    function test_it_logs_correct_query_count_and_time_and_resets_both_of_them()
    {
        $this->dispatchFakeQueryEvent(1);

        $this->testy->logAndResetQueryCount();
        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 1 → 1ms', $this->dumpCollector->shiftDump());

        $this->dispatchFakeQueryEvent(10);

        $this->testy->logAndResetQueryCount();
        $this->assertEquals(1, $this->dumpCollector->dumpCount());
        $this->assertEquals('query count: 1 → 10ms', $this->dumpCollector->shiftDump());
    }

    function test_it_contains_comment()
    {
        $this->testy->logAndResetQueryCount('test');
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

abstract class TinkeroundLogAndResetQueryCount extends Tinkeround
{
    public function handleFakeQueryExecutedEvent(QueryExecuted $event): void
    {
        $this->handleQueryExecutedEvent($event);
    }
}
