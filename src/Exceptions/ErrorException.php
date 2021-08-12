<?php

namespace WPTrail\Exceptions;

use ErrorException as BaseErrorException;

class ErrorException extends BaseErrorException
{
    public static function fromLastError(): ?self
    {
        if ($error = error_get_last()) {
            return new static($error['message'], 0, $error['type'], $error['file'], $error['line']);
        }

        return null;
    }

    public function isGloballySuppressed(): bool
    {
        return !(error_reporting() & $this->severity);
    }

    public function isLocallySuppressed(): bool
    {
        $paths = (array) apply_filters(
            'wptrail/suppressed-error-paths',
            $defaultPaths = [],
            $level = E_STRICT | E_DEPRECATED
        );

        foreach ($paths as $patten => $level) {
            $pathMatches  = (bool) preg_match($patten, $this->file);
            $levelMatches = $level & $this->severity;

            if ($pathMatches && $levelMatches) {
                return true;
            }
        }

        return false;
    }

    public function isFatal(): bool
    {
        return in_array($this->severity, [E_COMPILE_ERROR, E_CORE_ERROR, E_ERROR, E_PARSE], true);
    }
}
