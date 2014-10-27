<?php

namespace QualityCheck\Plugins\PhpCpd;

class TestRunnerFactory implements \QualityCheck\Plugins\TestRunnerFactory
{
    public function create()
    {
        $ymlConfig = new \Symfony\Component\Yaml\Parser();
        $config = new \QualityCheck\YmlConfig($ymlConfig);
        $testRunner = new \QualityCheck\Plugins\PhpCpd\TestRunner($config);

        return $testRunner;
    }
}
