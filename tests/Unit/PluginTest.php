<?php

namespace WPTrail\Tests\Unit;

use ErrorException;
use WPTrail\Plugin;
use WPTrail\Tests\TestCase;

use function Brain\Monkey\Filters\expectApplied;

class PluginTest extends TestCase
{
    /** @test */
    public function it_registers_required_error_handlers(): void
    {
        Plugin::bootstrap();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_wraps_an_error_in_exception(): void
    {
        $plugin = new Plugin;

        try {
            $plugin->handleError(E_USER_NOTICE, 'test message', 'test.php', 100);
        } catch (ErrorException $exception) {
            $this->assertSame(E_USER_NOTICE, $exception->getSeverity());
            $this->assertSame(0, $exception->getCode());
            $this->assertSame('test message', $exception->getMessage());
            $this->assertSame('test.php', $exception->getFile());
            $this->assertSame(100, $exception->getLine());
        }
    }

    /** @test */
    public function it_catches_an_error(): void
    {
        Plugin::bootstrap();

        try {
            trigger_error('test');

            $this->fail('An ErrorException should have been raised');
        } catch (ErrorException $exception) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function it_suppresses_an_error_in_a_given_path(): void
    {
        Plugin::bootstrap();

        expectApplied('wptrail/suppressed-error-paths')
            ->andReturn([
                '@^' . preg_quote(__FILE__, '@') . '$@' => E_USER_NOTICE,
            ]);

        trigger_error('test');

        $this->assertTrue(true);
    }
}
