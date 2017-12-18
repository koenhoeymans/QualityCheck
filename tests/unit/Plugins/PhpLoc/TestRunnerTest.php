<?php

namespace QualityCheck\Plugins\PhpLoc;

class TestRunnerTest extends \QualityCheck\TestUtils
{
    private $config;

    private $testResults;

    private $test;

    private $buildDir;

    private $logfile;

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
            . 'phploc' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
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
        $this->removeDir(sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phploc');
    }

    /**
     * @test
     */
    public function placesPhpCsCommandLineOutputInMapInBuildDir()
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
             ->will($this->returnValue(array()));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);

        $this->assertTrue(file_exists($this->logfile));
        $this->assertContains('Lines of Code', file_get_contents($this->logfile));
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
        $logfile = sys_get_temp_dir() . DIRECTORY_SEPARATOR .
            'phploc' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
        $this->testResults
             ->expects($this->once())
             ->method('addLogFile')
             ->with('PhpLoc log', 'phploc/cmdLog.txt');
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);
    }

    /**
     * @test
     */
    public function excludesFilesFromTest()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array('Foo.php')));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);
    }
}
