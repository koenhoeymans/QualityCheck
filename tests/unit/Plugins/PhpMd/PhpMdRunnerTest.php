<?php

namespace QualityCheck\Plugins\PhpMd;

class PhpMdRunnerTest extends \QualityCheck\TestUtils
{
    private $config;

    private $testResults;

    private $phpMdRunner;

    private $buildDir;

    private $logfile;

    public function setup()
    {
        $this->teardown();

        $this->config = $this->createMock('\\QualityCheck\\Config');
        $this->testResults = $this->getMockBuilder('\\QualityCheck\\ReportTestResults')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->phpMdRunner = new TestRunner($this->config);

        $this->buildDir = sys_get_temp_dir();
        $this->logfile = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpmd' . DIRECTORY_SEPARATOR . 'result.html';
    }

    public function teardown()
    {
        $this->removeDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phpmd');
    }

    /**
     * @test
     */
    public function placesPhpMdResultsInOwnDirectory()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(__FILE__));

        $this->phpMdRunner->reportTestResults($this->testResults);

        $this->assertTrue(file_exists($this->logfile));
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

        $this->testResults->expects($this->once())->method('addLogFile');

        $this->phpMdRunner->reportTestResults($this->testResults);
    }
}
