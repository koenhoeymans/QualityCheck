<?php

namespace Foo;

/**
 * Copied from detected duplication in PHPLoc
 */
class Bar
{
    public function testWithoutTests()
    {
        $expected =
          array(
            'files' => 1,
            'loc' => 70,
            'cloc' => 3,
            'ncloc' => 67,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 2,
            'methods' => 4,
            'publicMethods' => 2,
            'nonPublicMethods' => 2,
            'nonStaticMethods' => 3,
            'staticMethods' => 1,
            'constants' => 2,
            'classConstants' => 1,
            'globalConstants' => 1,
            'ccnByNom' => 1.5,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0,
            'testClasses' => 0,
            'testMethods' => 0,
            'methodCalls' => 6,
            'staticMethodCalls' => 4,
            'instanceMethodCalls' => 2,
            'attributeAccesses' => 6,
            'staticAttributeAccesses' => 4,
            'instanceAttributeAccesses' => 2,
            'lloc' => 25,
            'llocClasses' => 22,
            'namedFunctions' => 1,
            'ccnByLloc' => 0.08,
            'llocByNoc' => 11,
            'llocByNom' => 5.5,
            'llocFunctions' => 1,
            'llocGlobal' => 2,
            'llocByNof' => 0.5,
            'globalAccesses' => 4,
            'globalVariableAccesses' => 2,
            'superGlobalVariableAccesses' => 1,
            'globalConstantAccesses' => 1
        );

        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array(__DIR__ . '/_files/source.php'), FALSE
          ),
          '',
          0.01
        );
    }

    public function testWithTests()
    {
        $expected =
          array(
            'files' => 2,
            'loc' => 93,
            'cloc' => 7,
            'ncloc' => 86,
            'ccn' => 2,
            'ccnMethods' => 2,
            'interfaces' => 1,
            'classes' => 2,
            'abstractClasses' => 1,
            'concreteClasses' => 1,
            'anonymousFunctions' => 1,
            'functions' => 2,
            'methods' => 4,
            'publicMethods' => 2,
            'nonPublicMethods' => 2,
            'nonStaticMethods' => 3,
            'staticMethods' => 1,
            'constants' => 2,
            'classConstants' => 1,
            'globalConstants' => 1,
            'testClasses' => 1,
            'testMethods' => 2,
            'ccnByNom' => 1.5,
            'directories' => 0,
            'namespaces' => 1,
            'traits' => 0,
            'methodCalls' => 6,
            'staticMethodCalls' => 4,
            'instanceMethodCalls' => 2,
            'attributeAccesses' => 6,
            'staticAttributeAccesses' => 4,
            'instanceAttributeAccesses' => 2,
            'lloc' => 25,
            'llocClasses' => 22,
            'namedFunctions' => 1,
            'ccnByLloc' => 0.08,
            'llocByNoc' => 11,
            'llocByNom' => 5.5,
            'llocFunctions' => 1,
            'llocGlobal' => 2,
            'llocByNof' => 0.5,
            'globalAccesses' => 4,
            'globalVariableAccesses' => 2,
            'superGlobalVariableAccesses' => 1,
            'globalConstantAccesses' => 1
        );

        $this->assertEquals(
          $expected,
          $this->analyser->countFiles(
            array(
              __DIR__ . '/_files/source.php',
              __DIR__ . '/_files/tests.php'
            ), TRUE
          ),
          '',
          0.01
        );
    }
}
