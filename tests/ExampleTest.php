<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_base_endpoint_returns_a_successful_response()
    {
        $this->get('/');
        $expected = json_encode(['message' => 'Hello Lumen'. $this->app->version()]);
        $this->assertEquals($expected, $this->response->getContent());
    }
}
