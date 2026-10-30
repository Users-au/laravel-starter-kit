<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Http;

abstract class TestCase extends BaseTestCase
{
    // Laravel 12 boots the application's existing bootstrap/app.php.
    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
    }
}
