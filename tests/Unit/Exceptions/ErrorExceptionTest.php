<?php

namespace WPTrail\Tests\Unit\Exceptions;

use ErrorException as BaseErrorException;
use WPTrail\Exceptions\ErrorException;
use WPTrail\Tests\TestCase;

use function Brain\Monkey\Filters\expectApplied;

class ErrorExceptionTest extends TestCase
{
    /** @test */
    public function it_extends_from_base_error_exception(): void
    {
        $exception = new ErrorException();

        $this->assertInstanceOf(BaseErrorException::class, $exception);
    }

    /** @test */
    public function it_make_a_new_instance_from_last_error(): void
    {
        @strpos();

        $exception = ErrorException::fromLastError();

        $this->assertNotNull($exception);
    }

    /** @test */
    public function it_checks_whether_an_error_is_globally_suppressed(): void
    {
        $exception = new ErrorException(
            'test message',
            0,
            E_USER_NOTICE,
            __FILE__,
            100
        );

        $level = error_reporting(E_ALL ^ E_USER_NOTICE);

        $this->assertTrue($exception->isGloballySuppressed());

        error_reporting($level);
    }

    /** @test */
    public function it_checks_whether_an_error_is_locally_suppressed(): void
    {
        $exception = new ErrorException(
            'test message',
            0,
            E_USER_NOTICE,
            __FILE__,
            100
        );

        expectApplied('wptrail/suppressed-error-paths')
            ->andReturn([
                '@^' . preg_quote(__FILE__, '@') . '$@' => E_USER_NOTICE,
            ]);

        $this->assertTrue($exception->isLocallySuppressed());
    }
}
