<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FrontPageTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanLoadFrontPage()
    {
        //Front page comes from frontend container
        $this->get('/')->assertStatus(404);
    }
}
