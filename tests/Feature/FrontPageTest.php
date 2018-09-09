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
        $this->withExceptionHandling();

        $response = $this->get('/');

        //@TODO: Change this to 200 when we implement frontpage
        $response->assertStatus(404);
    }
}
