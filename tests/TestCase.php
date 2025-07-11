<?php

namespace Tests;

use App\Services\DataService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected DataService $dataService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataService = new DataService();
    }
}
