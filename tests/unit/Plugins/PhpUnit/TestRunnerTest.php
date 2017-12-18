<?php

namespace QualityCheck\Plugins\PhpUnit;

class TestRunnerTest extends \QualityCheck\TestUtils
{
    private $config;

    private $testResults;

    private $test;

    private $buildDir;

    private $logfile;

    private $composerBinDir;

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
            . 'phpunit' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
        $this->composerBinDir = realpath(__DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin') . DIRECTORY_SEPARATOR;
    }

    public function teardown()
    {
        $this->removeDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phpunit');
        $this->removeDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'codecoverage');
    }

    /**
     * @test
     */
    public function placesPhpUnitCommandLineOutputInMapInBuildDir()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(
                 __DIR__ . DIRECTORY_SEPARATOR . 'SampleProject'
             ));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);

        $this->assertTrue(file_exists($this->logfile));
        $this->assertContains('Time', file_get_contents($this->logfile));
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
             ->method('getProjectDir')
             ->will($this->returnValue(sys_get_temp_dir()));
        $this->testResults
             ->expects($this->once())
             ->method('addLogFile')
             ->with('PhpUnit log', 'phpunit/cmdLog.txt');
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);
    }

    /**
     * @test
     */
    public function addsCodeCoverageIfOptionGiven()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(
                 __DIR__ . DIRECTORY_SEPARATOR . 'SampleProject'
             ));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('hasTestOption')
             ->with('phpunit', 'codecoverage')
             ->will($this->returnValue(true));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);

        $this->assertTrue(
            file_exists(
                $this->buildDir . DIRECTORY_SEPARATOR
                . 'codecoverage' . DIRECTORY_SEPARATOR
                . 'index.html'
            )
        );
    }

    /**
     * @test
     */
    public function addsCodeCoverageLinkToTestResultsIfOptionSpecified()
    {
        $ccIndexFile = 'codecoverage/index.html';

        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(sys_get_temp_dir()));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('hasTestOption')
             ->with('phpunit', 'codecoverage')
             ->will($this->returnValue(true));
        $this->testResults
             ->expects($this->at(1))
             ->method('addLogFile')
             ->with(
                 'CodeCoverage',
                 $ccIndexFile
             );
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);
    }
}
