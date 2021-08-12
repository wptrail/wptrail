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
        $error = error_get_last();

        if ($error && $this->isFatal($error['type'])) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    protected function isFatal(int $level): bool
    {
        return in_array($level, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE], true);
    }
}
