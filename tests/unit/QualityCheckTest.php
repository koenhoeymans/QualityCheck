<?php

namespace QualityCheck;

class QualityCheckTest extends \PHPUnit\Framework\TestCase
{
    private $buildDir;

    private $file;

    private $config;

    private $qc;

    public function setup()
    {
        $this->buildDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'build';
        $this->file = $this->buildDir . DIRECTORY_SEPARATOR . 'index.html';
        $this->config = $this->createMock('\\QualityCheck\\Config');
        $this->qc = new \QualityCheck\QualityCheck($this->config);

        $this->teardown();
    }

    public function teardown()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * @test
     */
    public function createsBuildDir()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));

        $this->qc->build();

        $this->assertTrue(is_dir($this->buildDir));
    }

    /**
     * @test
     */
    public function throwsDoTestEvent()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $observer = $this->createMock('\\Epa\\Api\\Observer');
        $this->qc->addObserver($observer);
        $observer->expects($this->once())
                 ->method('notify')
                 ->with(new ReportTestResults());

        $this->qc->build();
    }

    /**
     * @test
     */
    public function writesTestReport()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));

        $this->qc->build();

        $this->assertTrue(file_exists($this->file));
    }

    /**
     * @test
     */
    public function writesTestLogsInTestReport()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));

        $plugin = new \QualityCheck\Plugins\MyUnit\TestRunner();
        $this->qc->addObserver($plugin);

        $this->qc->build();

        $this->assertContains('foo log', file_get_contents($this->file));
    }
}
