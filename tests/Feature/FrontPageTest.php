<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontPageTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanLoadFrontPage()
    {
        $this->get('/')->assertStatus(200)->assertViewIs('index');
    }
}
