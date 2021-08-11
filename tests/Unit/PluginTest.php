<?php

namespace WPTrail\Tests\Unit;

use ErrorException;
use WPTrail\Plugin;
use WPTrail\Tests\TestCase;

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
    public function it_catches_errors(): void
    {
        Plugin::bootstrap();

        try {
            trigger_error('test');

            $this->fail('Should not continue here, the error should have been caught');
        } catch (ErrorException $exception) {
            $this->assertTrue(true);

            return;
        }
    }
}
