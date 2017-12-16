<?php

namespace QualityCheck\Plugins\PhpMd;

class TestRunnerFactory implements \QualityCheck\Plugins\TestRunnerFactory
{
    public function create() : TestRunner
    {
        $ymlConfig = new \Symfony\Component\Yaml\Parser();
        $config = new \QualityCheck\YmlConfig($ymlConfig);
        $testRunner = new \QualityCheck\Plugins\PhpMd\TestRunner($config);

        return $testRunner;
    }
}
