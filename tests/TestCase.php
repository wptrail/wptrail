<?php

namespace WPTrail\Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;

abstract class TestCase extends MockeryTestCase
{
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
