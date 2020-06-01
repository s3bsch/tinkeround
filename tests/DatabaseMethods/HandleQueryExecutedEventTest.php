<?php

namespace Tinkeround\Tests\DatabaseMethods;

use Illuminate\Database\Connection;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Tinkeround\Tests\TestCase;
use Tinkeround\Tinkeround;

/**
 * Test for the `handleQueryExecutedEvent()` method.
 */
class HandleQueryExecutedEventTest extends TestCase
{
    protected $preventDumpCollector = true;

    /** @var TinkeroundHandleQueryExecutedEvent */
    protected $testy;

    function setUp(): void
    {
        parent::setUp();
        $this->testy = $this->createTinkeroundMock(TinkeroundHandleQueryExecutedEvent::class);
    }

    function test_total_query_count_is_zero_when_making_no_queries()
    {
        $totalQueryCount = $this->testy->totalQueryCount();
        $this->assertEquals(0, $totalQueryCount);
    }

    function test_total_query_time_is_zero_when_making_no_queries()
    {
        $time = $this->testy->totalQueryTime();
        $this->assertEquals(0.0, $time);
    }

    function test_creation_of_database_connection_stub()
    {
        $connection = $this->createDatabaseConnectionStub();
        $this->assertEquals('fake', $connection->getName());
    }

    /**
     * @depends test_creation_of_database_connection_stub
     */
    function test_fireQueryExecutedEvent()
    {
        $this->expectExceptionMessage('fake sql');

        /** @noinspection PhpUndefinedMethodInspection */
        DB::listen(function (QueryExecuted $event) {
            throw new \Exception($event->sql);
        });

        $this->fireQueryExecutedEvent();
    }

    /**
     * @depends test_fireQueryExecutedEvent
     */
    function test_total_query_count_after_simulating_a_query()
    {
        $this->testy->enableHandlingOfQueryExecutedEvents();

        $this->fireQueryExecutedEvent();

        $totalQueryCount = $this->testy->totalQueryCount();
        $this->assertEquals(1, $totalQueryCount);
    }

    private function createDatabaseConnectionStub()
    {
        $connection = $this->createMock(Connection::class);

        $connection->method('getName')
            ->willReturn('fake');

        /** @var Connection $connection */
        return $connection;
    }

    private function fireQueryExecutedEvent(): void
    {
        $connection = $this->createDatabaseConnectionStub();
        $queryExecuted = new QueryExecuted('fake sql', [], 0.0, $connection);

        event($queryExecuted);
    }
}

abstract class TinkeroundHandleQueryExecutedEvent extends Tinkeround
{
    public function enableHandlingOfQueryExecutedEvents(): void
    {
        $this->registerQueryListener();
    }
}