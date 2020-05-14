<?php

namespace Tinkeround\Tests\DatabaseMethods;

use Illuminate\Database\Events\QueryExecuted;
use Tinkeround\Tests\DumpCollector;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `logQuery()` method.
 */
class LogQueryTest extends TestCase
{
    /** @var TinkeroundLogQuery */
    private $testy;

    function setUp(): void
    {
        $this->testy = $this->createTinkeroundMock(TinkeroundLogQuery::class);
    }

    function test_it_does_not_log_query_by_default()
    {
        $collector = new DumpCollector();

        $this->dispatchFakeQueryEvent();
        $this->assertEquals(0, $collector->dumpCount());
    }

    function test_it_logs_query_in_case_logging_is_enabled()
    {
        $collector = new DumpCollector();

        $this->testy->enableQueryLogging();
        $this->dispatchFakeQueryEvent();

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('fake sql', $collector->shiftDump());
    }

    function test_it_does_not_log_queries_after_disabling_logging()
    {
        $collector = new DumpCollector();

        $this->testy->enableQueryLogging();
        $this->dispatchFakeQueryEvent('fake sql 1');

        $this->testy->disableQueryLogging();
        $this->dispatchFakeQueryEvent('fake sql 2');

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('fake sql 1', $collector->shiftDump());
    }

    function test_it_logs_bindings_in_case_logging_of_bindings_is_enabled()
    {
        $collector = new DumpCollector();
        $this->testy->enableQueryLogging(true);

        $this->dispatchFakeQueryEvent();

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals('fake sql', $collector->shiftDump());
        $this->assertEquals('bindings: `[]`', $collector->shiftDump());
    }

    /** @noinspection PhpUndefinedFieldInspection */
    private function createFakeQueryEvent(string $sql, array $bindings = [])
    {
        $fakeQuery = $this->createMock(QueryExecuted::class);
        $fakeQuery->sql = $sql;
        $fakeQuery->bindings = $bindings;

        /** @var QueryExecuted $fakeQuery */
        return $fakeQuery;
    }

    private function dispatchFakeQueryEvent(string $sql = 'fake sql'): void
    {
        $event = $this->createFakeQueryEvent($sql);
        $this->testy->handleFakeQueryExecutedEvent($event);
    }
}

abstract class TinkeroundLogQuery extends Tinkeround
{
    public function handleFakeQueryExecutedEvent(QueryExecuted $event): void
    {
        $this->handleQueryExecutedEvent($event);
    }
}
