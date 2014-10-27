<?php

namespace QualityCheck\Plugins;

class Registrar implements \Epa\Api\Plugin
{
    private $testRunnerFactory;

    public function __construct(TestRunnerFactory $factory)
    {
        $this->testRunnerFactory = $factory;
    }

    public function registerHandlers(\Epa\Api\EventDispatcher $eventDispatcher)
    {
        $eventDispatcher->registerForEvent(
            'QualityCheck\\ReportTestResults',
            array($this, 'initTest')
        );
    }

    public function initTest(\QualityCheck\ReportTestResults $testResults)
    {
        $testRunner = $this->testRunnerFactory->create();
        $testRunner->reportTestResults($testResults);
    }
}
