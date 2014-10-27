<?php

namespace QualityCheck\Plugins\PhpCs;

class TestRunnerFactory implements \QualityCheck\Plugins\TestRunnerFactory
{
    public function create()
    {
        $ymlConfig = new \Symfony\Component\Yaml\Parser();
        $config = new \QualityCheck\YmlConfig($ymlConfig);
        $testRunner = new \QualityCheck\Plugins\PhpCs\TestRunner($config);

        return $testRunner;
    }
}
