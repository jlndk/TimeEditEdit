<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelpersTest extends TestCase
{
    /**
     * Check if the natural_implode helper function actually concatenates
     * multiple (more than two) array elements correctly
     *
     * @test
     * @return void
     */
    public function naturalImplodeBehavesProperlyWithMultipleItems()
    {
        $arr = [
            'foo', 'bar', 'bas', 'cux'
        ];

        $this->assertEquals('foo, bar, bas & cux', natural_implode($arr));
    }

    /**
     * Check if the natural_implode helper function actually concatenates
     * two array elements correctly
     *
     * @test
     * @return void
     */
    public function naturalImplodeBehavesProperlyWithTwoItems()
    {
        $arr = [
            'foo', 'bar'
        ];

        $this->assertEquals('foo & bar', natural_implode($arr));
    }

    /**
     * Check if the natural_implode helper function actually concatenates
     * an array with a single element inside correctly
     *
     * @test
     * @return void
     */
    public function naturalImplodeBehavesProperlyWithOneItem()
    {
        $arr = [
            'foo'
        ];

        $this->assertEquals('foo', natural_implode($arr));
    }

    /**
     * Check if the natural_implode helper function returns an empty string
     * if an empty array is supplied
     *
     * @test
     * @return void
     */
    public function natualImplodeShouldReturnNothingIfAnEmptyArrayIsSupplied()
    {
        $this->assertEquals('', natural_implode([]));
    }
}
