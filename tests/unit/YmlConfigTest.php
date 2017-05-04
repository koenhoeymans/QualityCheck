<?php

namespace QualityCheck;

class YmlConfigTest extends \PHPUnit\Framework\TestCase
{
    private $parser;

    private $config;

    public function setup()
    {
        $this->parser = $this->getMockBuilder('\\Symfony\\Component\\Yaml\\Parser')
                             ->disableOriginalConstructor()
                             ->getMock();
        $this->config = new YmlConfig($this->parser);
    }

    /**
     * @test
     */
    public function hasListOfAllTestNames()
    {
        $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('tests' => array('x' => null, 'y' => null))
             ));
        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertEquals(array('x', 'y'), $this->config->getTestNames());
    }

    /**
     * @test
     */
    public function knowsWhatShouldBeIgnored()
    {
        $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('settings' => array('ignore' => array('vendor')))
             ));
        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertEquals(array('vendor'), $this->config->getToIgnore());
    }

    /**
     * @test
     */
    public function emptyListWhenNothingToIgnore()
    {
        $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('settings' => array('settings' => array('vendor')))
             ));
        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertEquals(array(), $this->config->getToIgnore());
    }

    /**
     * @test
     */
    public function ifNoDirectorySpecifiedCwdIsUsedAsProjectDir()
    {
        unset($_SERVER['argv'][1]);
        $cwd = getCwd();

        $this->assertEquals($cwd, $this->config->getProjectDir());
    }

    /**
     * @test
     */
    public function ifDirectorySpecifiedItIsUsedAsProjectDir()
    {
        $_SERVER['argv'][1] = sys_get_temp_dir();

        $this->assertEquals(sys_get_temp_dir(), $this->config->getProjectDir());
    }

    /**
     * @test
     */
    public function defaultBuildDirIsSubDirCalledBuildInProjectDir()
    {
        $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('settings' => array('foo' => 'bar'))
             ));
        $projectDir = __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR;
        chdir($projectDir);
        $_SERVER['argv'] = array(
            __DIR__ . DIRECTORY_SEPARATOR . 'qc',
            $projectDir
        );

        $this->assertEquals(
            realpath($projectDir . DIRECTORY_SEPARATOR . 'build'),
            $this->config->getBuildDir()
        );
    }

    /**
     * @test
     */
    public function specifiedBuildDirCanBeRelativeToProjectDir()
    {
        $this->parser
              ->expects($this->atLeastOnce())
              ->method('parse')
              ->will($this->returnValue(
                  array('settings' => array('build_dir' => 'build'))
              ));
        $projectDir = __DIR__ . DIRECTORY_SEPARATOR
             . '..' . DIRECTORY_SEPARATOR
             . '..' . DIRECTORY_SEPARATOR;
        chdir($projectDir);
        $_SERVER['argv'] = array(
            __DIR__ . DIRECTORY_SEPARATOR . 'qc',
            $projectDir
        );

        $this->assertEquals(
            realpath($projectDir . DIRECTORY_SEPARATOR . 'build'),
            $this->config->getBuildDir()
        );
    }

    /**
     * @test
     */
    public function buildDirIsTakenAsSpecifiedWhenFullPath()
    {
        $buildDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'build';
        $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('settings' => array('build_dir' => $buildDir))
             ));

        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertEquals($buildDir, $this->config->getBuildDir());
    }

    /**
     * @test
     */
    public function buildDirIsTranslatedToFullPathWhenSpecifiedRelativeDir()
    {
        $buildDir = __DIR__ . DIRECTORY_SEPARATOR . 'build';
        $this->parser
            ->expects($this->atLeastOnce())
            ->method('parse')
            ->will($this->returnValue(
                array('settings' => array('build_dir' => 'build'))
            ));

        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertEquals($buildDir, $this->config->getBuildDir());
    }

    /**
     * @test
     */
    public function knowsTestOptionIsNotSpecified()
    {
         $this->parser
              ->expects($this->atLeastOnce())
              ->method('parse')
              ->will($this->returnValue(
                  array('tests' => array('fee' => array('bar')))
              ));
        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertFalse($this->config->hasTestOption('foo', 'bar'));
    }

    /**
     * @test
     */
    public function knowsTestOptionIsSpecified()
    {
         $this->parser
             ->expects($this->atLeastOnce())
             ->method('parse')
             ->will($this->returnValue(
                 array('tests' => array('foo' => array('bar')))
             ));
        $_SERVER['argv'] = array(__DIR__ . DIRECTORY_SEPARATOR . 'qc', __DIR__);

        $this->assertTrue($this->config->hasTestOption('foo', 'bar'));
    }
}
