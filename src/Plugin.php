<?php

namespace WPTrail;

use Throwable;
use WPTrail\Exceptions\ErrorException;

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

        $self = new static();

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

        if ($exception->isGloballySuppressed()) {
            return false;
        }

        if ($exception->isLocallySuppressed()) {
            return true;
        }

        throw $exception;
    }

    public function handleShutdown(): void
    {
        //
    }
}