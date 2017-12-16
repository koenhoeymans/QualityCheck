<?php

namespace QualityCheck\Plugins\PhpUnit;

class TestRunnerFactory implements \QualityCheck\Plugins\TestRunnerFactory
{
    public function create() : TestRunner
    {
        $ymlConfig = new \Symfony\Component\Yaml\Parser();
        $config = new \QualityCheck\YmlConfig($ymlConfig);
        $testRunner = new \QualityCheck\Plugins\PhpUnit\TestRunner($config);

        return $testRunner;
    }
}
