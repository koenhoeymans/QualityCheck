<?php

namespace Foo;

require __DIR__ . DIRECTORY_SEPARATOR
    . '..'      . DIRECTORY_SEPARATOR
    . 'src'     . DIRECTORY_SEPARATOR
    . 'Foo.php';

class FooTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group e2eSubtest
     */
    public function returnSum()
    {
        $foo = new \Foo\Foo();
        $this->assertSame(3, $foo->returnsInt3());
    }

    /**
     * @test
     * @group e2eSubtest
     */
    public function aFailingTest()
    {
        $this->assertTrue(false);
    }
}
