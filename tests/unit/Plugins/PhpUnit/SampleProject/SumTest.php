<?php

namespace QualityCheck\Plugins\PhpUnit\SampleProject;

class SumTest extends \QualityCheck\TestUtils
{
    /**
     * @test
     */
    public function onePlusOneIsTwo()
    {
        $this->assertEquals(2, 1+1);
    }
}
