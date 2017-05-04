<?php

namespace QualityCheck\Plugins\PhpCpd;

class TestRunnerTest extends \QualityCheck\TestUtils
{
    private $config;

    private $testResults;

    private $test;

    private $buildDir;

    private $logfile;

    private $cmdline;

    public function setup()
    {
        $this->teardown();

        $this->config = $this->createMock('\\QualityCheck\\Config');
        $this->testResults = $this->getMockBuilder('\\QualityCheck\\ReportTestResults')
                                  ->disableOriginalConstructor()
                                  ->getMock();

        $this->test = new TestRunner($this->config);

        $this->buildDir = sys_get_temp_dir();
        $this->logfile = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
        $this->cmdline = __DIR__ . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . 'vendor'
            . DIRECTORY_SEPARATOR . 'bin'
            . DIRECTORY_SEPARATOR . 'phpcpd ' . sys_get_temp_dir();
    }

    public function teardown()
    {
        $this->removeDir($this->buildDir . DIRECTORY_SEPARATOR . 'phpcpd');
    }

    /**
     * @test
     */
    public function placesCpdCommandLineOutputInMapInBuildDir()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array()));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(__DIR__));

        $this->test->reportTestResults($this->testResults);

        $this->assertTrue(file_exists($this->logfile));
        $this->assertContains('duplicated lines', file_get_contents($this->logfile));
    }

    /**
     * @test
     */
    public function addsLogFileToTestResults()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array()));
        $this->testResults
             ->expects($this->once())
             ->method('addLogFile')
             ->with('PhpCpd log', 'phpcpd/cmdLog.txt');

        $this->test->reportTestResults($this->testResults);
    }

    /**
     * @test
     */
    public function ignoresFilesGloballySpecified()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(__DIR__));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array('vendor')));

        $this->test->reportTestResults($this->testResults);
    }
}
