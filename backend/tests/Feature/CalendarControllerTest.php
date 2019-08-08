<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class CalendarControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $mock = new MockHandler([
            new Response(200, ['content-type' => 'text/html; charset=UTF-8']),
        ]);

        $client = new Client([
            'handler' => HandlerStack::create($mock)
        ]);

        $this->app->instance(Client::class, $client);
    }

    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function fetchingAnInvalidCalendarShouldResultInA404StatusCode()
    {
        $this->get('/abc123')->assertStatus(404);
    }
}
