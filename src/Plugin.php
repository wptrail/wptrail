<?php

namespace WPTrail;

use ErrorException;
use Throwable;

class Plugin
{
    protected static bool $bootstrapped = false;

    protected static string $reservedMemory;

    public static function bootstrap(): void
    {
        if (static::$bootstrapped) {
            return;
        }

        static::$reservedMemory = str_repeat('x', 10240);

        $self = new static;

        error_reporting(-1);

        set_error_handler([$self, 'handleError']);
        set_exception_handler([$self, 'handleException']);
        register_shutdown_function([$self, 'handleShutdown']);
    }

    public function handleException(Throwable $throwable): void
    {
        //
    }

    public function handleError(
        int $level,
        string $message,
        string $file = '',
        int $line = 0,
        array $context = []
    ): bool {
        $exception = new ErrorException($message, 0, $level, $file, $line);
        throw $exception;

        return false;
    }

    public function handleShutdown(): void
    {
        //
    }
}
