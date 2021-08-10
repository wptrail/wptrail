<?php

namespace WPTrail\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as BaseTestCase;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

abstract class TestCase extends BaseTestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        setUp();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        tearDown();

        parent::tearDown();
    }
}
