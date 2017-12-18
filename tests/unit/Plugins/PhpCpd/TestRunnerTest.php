<?php

namespace QualityCheck\Plugins\PhpCpd;

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
            . 'phpcpd' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
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
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

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
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

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
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->test->reportTestResults($this->testResults);
    }

    /**
     * @test
     */
    public function ignoresFilesAndDirectoriesThroughRegex()
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
             ->will($this->returnValue(array('vendor', 'composer.json')));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->assertRegExp('/\'\~vendor\|composer\\\.json\~\'/', $this->test->getCommand());

        $this->test->reportTestResults($this->testResults);
    }
}
