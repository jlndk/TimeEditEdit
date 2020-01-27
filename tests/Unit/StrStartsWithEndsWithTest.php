<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class StrStartsWithEndsWithTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    public function itCanTellIfStringStartsWithString()
    {
        $this->assertTrue(str_starts_with("foobar", "foo"));
    }

    /**
     * @test
     * @return void
     */
    public function itCanTellIfStringDoesNotStartWithString()
    {
        $this->assertFalse(str_starts_with("foobar", "bar"));
    }

    /**
     * @test
     * @return void
     */
    public function itCanTestMultiplePrefixes()
    {
        $this->assertTrue(str_starts_with("foobar", "foo", "bas"));
        $this->assertTrue(str_starts_with("bazbar", "foo", "baz"));
    }

    /**
     * @test
     * @return void
     */
    public function itCanTellIfStringEndsWithString()
    {
        $this->assertTrue(str_ends_with("foobar", "bar"));
    }

    /**
     * @test
     * @return void
     */
    public function itCanTellIfStringDoesNotEndWithString()
    {
        $this->assertFalse(str_ends_with("foobar", "foo"));
    }

    /**
     * @test
     * @return void
     */
    public function itCanTestMultipleSuffixes()
    {
        $this->assertTrue(str_ends_with("barfoo", "foo", "bas"));
        $this->assertTrue(str_ends_with("barbaz", "bar", "baz"));
        $this->assertFalse(str_ends_with("barbaz", "bar", "biz"));
    }
}
