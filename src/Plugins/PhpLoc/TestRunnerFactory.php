<?php

namespace QualityCheck\Plugins\PhpLoc;

class TestRunnerFactory implements \QualityCheck\Plugins\TestRunnerFactory
{
    public function create()
    {
        $ymlConfig = new \Symfony\Component\Yaml\Parser();
        $config = new \QualityCheck\YmlConfig($ymlConfig);
        $testRunner = new \QualityCheck\Plugins\PhpLoc\TestRunner($config);

        return $testRunner;
    }
}
