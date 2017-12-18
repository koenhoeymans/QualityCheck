<?php

namespace QualityCheck\Plugins\PhpMd;

class PhpMdRunnerTest extends \QualityCheck\TestUtils
{
    private $config;

    private $testResults;

    private $phpMdRunner;

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

        $this->phpMdRunner = new TestRunner($this->config);

        $this->buildDir = sys_get_temp_dir();
        $this->logfile = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpmd' . DIRECTORY_SEPARATOR . 'cmdLog.txt';
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
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array('Foo.php')));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

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
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(__FILE__));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array('Foo.php')));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->testResults->expects($this->once())->method('addLogFile');

        $this->phpMdRunner->reportTestResults($this->testResults);
    }

    /**
     * @test
     */
    public function excludesDirectories()
    {
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getBuildDir')
             ->will($this->returnValue($this->buildDir));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getProjectDir')
             ->will($this->returnValue(__FILE__));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getToIgnore')
             ->will($this->returnValue(array('Foo.php')));
        $this->config
             ->expects($this->atLeastOnce())
             ->method('getComposerBinDir')
             ->will($this->returnValue($this->composerBinDir));

        $this->phpMdRunner->reportTestResults($this->testResults);
    }
}
