<?php

namespace Tinkeround\Traits;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

/**
 * Trait including database related methods.
 */
trait DatabaseMethods
{
    /** @var bool If true, bindings are logged additionally to executed database query */
    protected $logBindings = false;

    /** @var bool If true, executed database queries are logged */
    protected $logQueries = false;

    /** @var int Query count since last reset */
    protected $queryCount = 0;

    /** @var int Total count of queries made during tinkeround session */
    protected $queryCountTotal = 0;

    /** @var float Query time since last reset in milliseconds */
    protected $queryTime = 0.0;

    /** @var float Overall query time during tinkeround session in milliseconds */
    protected $queryTimeTotal = 0.0;

    /**
     * Disable logging of executed database queries.
     */
    public function disableQueryLogging(): void
    {
        $this->logQueries = false;
    }

    /**
     * Enable logging of executed database queries.
     *
     * @param bool $logBindings (optional) True to enable logging of bindings in addition to query
     */
    public function enableQueryLogging($logBindings = false): void
    {
        $this->logQueries = true;
        $this->logBindings = $logBindings;
    }

    /**
     * @return int Total count of queries made during tinkeround session
     */
    public function totalQueryCount(): int
    {
        return $this->queryCountTotal;
    }

    /**
     * @return float Overall query time during tinkeround session in milliseconds
     */
    public function totalQueryTime(): float
    {
        return $this->queryTimeTotal;
    }

    protected function handleQueryExecutedEvent(QueryExecuted $event): void
    {
        $this->queryCount++;
        $this->queryCountTotal++;

        $this->queryTime += $event->time;
        $this->queryTimeTotal += $event->time;

        $this->logQuery($event);
    }

    /**
     * Log executed database query in case query logging is enabled.
     * Additionally log bindings in case logging of bindings is enabled.
     *
     * @param QueryExecuted $event Executed database query event
     */
    protected function logQuery(QueryExecuted $event): void
    {
        if ($this->logQueries) {
            $this->log($event->sql);

            if ($this->logBindings) {
                $this->log('bindings:', $event->bindings);
            }
        }
    }

    protected function registerQueryListener(): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        DB::listen(function (QueryExecuted $query) {
            $this->handleQueryExecutedEvent($query);
        });
    }
}
