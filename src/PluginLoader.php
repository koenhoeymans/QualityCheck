<?php

namespace QualityCheck;

class PluginLoader
{
    private $eventDispatcher;

    private $testNames = array(
        'PHPUNIT' => 'PhpUnit',
        'PHPLOC' => 'PhpLoc',
        'PHPCS' => 'PhpCs',
        'PHPCPD' => 'PhpCpd',
        'PHPMD' => 'PhpMd'
    );

    public function __construct(
        \Epa\Api\EventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function loadFromConfig(Config $config)
    {
        foreach ($config->getTestNames() as $testName) {
            if (isset($this->testNames[strtoupper($testName)])) {
                $testName = $this->testNames[strtoupper($testName)];
            }

            $testRunnerFactory = '\\QualityCheck\\Plugins\\'
                . $testName . '\\TestRunnerFactory';
            $testRunnerFactory = new $testRunnerFactory();
            $registrar = new \QualityCheck\Plugins\Registrar($testRunnerFactory);

            $this->eventDispatcher->addPlugin($registrar);
        }
    }
}
