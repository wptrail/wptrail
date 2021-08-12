<?php

namespace WPTrail\Exceptions;

use ErrorException as BaseErrorException;

class ErrorException extends BaseErrorException
{
    public function isGloballySuppressed(): bool
    {
        return !(error_reporting() & $this->severity);
    }

    public function isLocallySuppressed(): bool
    {
        foreach ($this->locallySuppressedPaths() as $patten => $level) {
            $pathMatches  = (bool) preg_match($patten, $this->file);
            $levelMatches = $level & $this->severity;

            if ($pathMatches && $levelMatches) {
                return true;
            }
        }

        return false;
    }

    protected function locallySuppressedPaths(): array
    {
        /** @psalm-suppress TooManyArguments */
        return (array) apply_filters(
            'wptrail/suppressed-error-paths',
            $defaultPaths = [],
            $defaultLevel = E_STRICT | E_DEPRECATED
        );
    }
}
