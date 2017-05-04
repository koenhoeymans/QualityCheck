<?php

namespace QualityCheck;

class PluginLoaderTest extends \PHPUnit\Framework\TestCase
{
    private $eventDispatcher;

    public function setup()
    {
        $this->eventDispatcher = $this->createMock('\\Epa\\Api\\EventDispatcher');
        $this->pluginLoader = new \QualityCheck\PluginLoader(
            $this->eventDispatcher
        );
    }

    /**
     * @test
     */
    public function loadsRegistrarForPluginsFromConfig()
    {
        $config = $this->createMock('\\QualityCheck\\Config');
        $config->expects($this->atLeastOnce())
               ->method('getTestNames')
               ->will($this->returnValue(array('MyUnit')));
        $this->eventDispatcher
             ->expects($this->once())
             ->method('addPlugin')
             ->with(new \QualityCheck\Plugins\Registrar(
                 new \QualityCheck\Plugins\MyUnit\TestRunnerFactory()
             ));

        $this->pluginLoader->loadFromConfig($config);
    }

    /**
     * @test
     */
    public function pluginNamesFromConfigAreCaseInsensitive()
    {
        $config = $this->createMock('\\QualityCheck\\Config');
        $config->expects($this->atLeastOnce())
               ->method('getTestNames')
               ->will($this->returnValue(array('pHpUniT')));
        $this->eventDispatcher
             ->expects($this->once())
             ->method('addPlugin')
             ->with(new \QualityCheck\Plugins\Registrar(
                 new \QualityCheck\Plugins\PhpUnit\TestRunnerFactory()
             ));

        $this->pluginLoader->loadFromConfig($config);
    }
}
