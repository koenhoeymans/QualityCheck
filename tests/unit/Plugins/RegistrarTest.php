<?php

namespace QualityCheck\Plugins;

class RegistrarTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    private $registrar;

    public function setup()
    {
        $this->factory = $this->getMock(
            '\\QualityCheck\\Plugins\\TestRunnerFactory'
        );
        $this->registrar = new \QualityCheck\Plugins\Registrar($this->factory);
    }

    /**
     * @test
     */
    public function registersPhpCpdToReportTestResults()
    {
        $eventDispatcher = $this->getMock('\\Epa\\Api\\EventDispatcher');
        $eventDispatcher->expects($this->once())
                        ->method('registerForEvent')
                        ->with(
                            'QualityCheck\\ReportTestResults',
                            array($this->registrar, 'initTest')
                        );

        $this->registrar->registerHandlers($eventDispatcher);
    }

    /**
     * @test
     */
    public function initiatesPhpCpdTestOnEvent()
    {
        $event = $this->getMock('\\QualityCheck\\ReportTestResults');
        $testRunner = $this->getMockBuilder(
            '\\QualityCheck\\Plugins\\PhpCpd\\TestRunner'
        )->disableOriginalConstructor()->getMock();

        $this->factory->expects($this->once())
             ->method('create')
             ->will($this->returnValue($testRunner));
        $testRunner->expects($this->once())
                   ->method('reportTestResults')
                   ->with($event);

        $this->registrar->initTest($event);
    }
}
