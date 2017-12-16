<?php

namespace QualityCheck\Plugins;

class Registrar implements \Epa\Api\Plugin
{
    private $testRunnerFactory;

    public function __construct(TestRunnerFactory $factory)
    {
        $this->testRunnerFactory = $factory;
    }

    public function registerHandlers(\Epa\Api\EventDispatcher $eventDispatcher) : void
    {
        $eventDispatcher->registerForEvent(
            'QualityCheck\\ReportTestResults',
            array($this, 'initTest')
        );
    }

    public function initTest(\QualityCheck\ReportTestResults $testResults) : void
    {
        $testRunner = $this->testRunnerFactory->create();
        $testRunner->reportTestResults($testResults);
    }
}
