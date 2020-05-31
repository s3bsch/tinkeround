<?php

namespace Tinkeround\Traits;

use Countable;

/**
 * Trait including log methods.
 */
trait LogMethods
{
    /**
     * Log one or more arguments.
     *
     * In case the argument is a string, the first one is right trimmed,
     * subsequent string arguments are trimmed at both sides.
     *
     * In case the argument is null, if it's the only argument it's dumped as is,
     * otherwise its string representation is concatenated.
     *
     * In case the argument is a boolean, if it's the only argument it's dumped as is,
     * otherwise its string representation is concatenated.
     *
     * In case the argument is an integer, if it's the only argument it's dumped as is,
     * otherwise its string representation is concatenated.
     *
     * In case the argument is an array, if it's the only argument and is empty it's dumped as is,
     * otherwise its string representation is concatenated.
     *
     * In case the argument is an object, the string representation of its class name is concatenated.
     *
     * @param mixed $arg
     * @param mixed ...$more
     */
    public function log($arg, ...$more): void
    {
        if ((func_num_args() === 1)) {
            $this->logSingleArgument($arg);
        } else {
            $args = func_get_args();
            $this->logMultipleArguments($args);
        }
    }

    /**
     * Log number of items contained in given list.
     *
     * @param array|Countable $list List of items which are counted
     * @param string $name (optional) Name for count (context), defaults to 'count'
     */
    public function logCount($list, string $name = null): void
    {
        $name = $name ?? 'Count';
        $count = count($list);

        $this->log("{$name}: {$count}");
    }

    /**
     * Log type of given variable. Class name in case object is given, otherwise the data type.
     *
     * @param mixed $var Variable for which the type is logged
     */
    public function logType($var): void
    {
        $type = gettype($var);
        $type = strtolower($type);

        if (is_string($var) || is_object($var)) {
            $value = $var;

            if (is_object($var)) {
                $value = get_class($var);
            }

            $this->log('Type:', "`{$type}`", "({$value})");
        } else {
            if (is_float($var)) {
                $type = 'float';
            }

            $this->log('Type:', "`{$type}`");
        }
    }

    private function logSingleArgument($arg): void
    {
        if (is_string($arg)) {
            $string = rtrim($arg);
            $this->dump($string);
        } else if (is_bool($arg) || is_int($arg) || empty($arg)) {
            $this->dump($arg);
        } else {
            $this->logMultipleArguments([$arg]);
        }
    }

    private function logMultipleArguments(array $args): void
    {
        $line = '';

        foreach ($args as $index => $arg) {
            if (is_string($arg)) {
                $line .= (($index === 0) ? rtrim($arg) : trim($arg));
            } else if (is_object($arg)) {
                $className = get_class($arg);
                $line .= "`{$className}`";
            } else {
                $string = json_encode($arg);
                $line .= "`{$string}`";
            }

            $line .= ' ';
        }

        $line = rtrim($line);
        $this->dump($line);
    }
}
