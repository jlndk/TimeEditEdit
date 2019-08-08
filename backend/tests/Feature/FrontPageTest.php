<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FrontPageTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     * @return void
     */
    public function itCanLoadFrontPage()
    {
        //Front pagecomes from frontend container
        $this->get('/')->assertStatus(404);
    }
}
