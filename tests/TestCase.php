<?php

namespace Tests;

use App\Models\Customer;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
