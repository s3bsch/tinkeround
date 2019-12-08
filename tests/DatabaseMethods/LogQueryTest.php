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
    /** @var Tinkeround */
    private $testy;

    function setUp(): void
    {
        $this->testy = $this->createTinkeroundMock();
    }

    function test_it_does_not_log_query_by_default()
    {
        $collector = new DumpCollector();

        $query = $this->createFakeQuery('fake sql');
        $this->testy->logQuery($query);

        $this->assertEquals(0, $collector->dumpCount());
    }

    function test_it_logs_query_in_case_logging_is_enabled()
    {
        $collector = new DumpCollector();
        $this->testy->enableQueryLogging();

        $query = $this->createFakeQuery('fake sql');
        $this->testy->logQuery($query);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('fake sql', $collector->shiftDump());
    }

    function test_it_does_not_log_queries_after_disabling_logging()
    {
        $collector = new DumpCollector();
        $this->testy->enableQueryLogging();

        $query = $this->createFakeQuery('fake sql 1');
        $this->testy->logQuery($query);

        $this->testy->disableQueryLogging();

        $query = $this->createFakeQuery('fake sql 2');
        $this->testy->logQuery($query);

        $this->assertEquals(1, $collector->dumpCount());
        $this->assertEquals('fake sql 1', $collector->shiftDump());
    }

    function test_it_logs_bindings_in_case_logging_of_bindings_is_enabled()
    {
        $collector = new DumpCollector();
        $this->testy->enableQueryLogging(true);

        $query = $this->createFakeQuery('fake sql');
        $this->testy->logQuery($query);

        $this->assertEquals(2, $collector->dumpCount());
        $this->assertEquals('fake sql', $collector->shiftDump());
        $this->assertEquals('bindings: `[]`', $collector->shiftDump());
    }

    /** @noinspection PhpUndefinedFieldInspection */
    private function createFakeQuery(string $sql, array $bindings = [])
    {
        $fakeQuery = $this->createMock(QueryExecuted::class);
        $fakeQuery->sql = $sql;
        $fakeQuery->bindings = $bindings;

        /** @var QueryExecuted $fakeQuery */
        return $fakeQuery;
    }
}
