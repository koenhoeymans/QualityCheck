<?php

namespace QualityCheck;

class YmlConfig implements Config
{
    private $yamlParser;

    private $settings;

    public function __construct(\Symfony\Component\Yaml\Parser $parser)
    {
        $this->yamlParser = $parser;
    }

    public function getBuildDir() : string
    {
        $settings = $this->getSettings();

        if (isset($settings['settings']['build_dir'])) {
            $buildDir = $settings['settings']['build_dir'];
        } else {
            return $this->getProjectDir() . DIRECTORY_SEPARATOR . 'build';
        }
        
        if (preg_match('@^[a-zA-Z]:|^/@', $buildDir)) {
            return $buildDir;
        } else {
            return $this->getProjectDir() . DIRECTORY_SEPARATOR . $buildDir;
        }
    }

    protected function getSettings() : array
    {
        if (!isset($this->settings)) {
            $file = $this->getProjectDir() . DIRECTORY_SEPARATOR . 'qc.yml';
            $settings = $this->yamlParser->parse(file_get_contents($file));
            $this->settings = $settings;
        }

        return $this->settings;
    }

    public function getProjectDir() : string
    {
        if (!isset($_SERVER['argv'][1])) {
            $baseDir = getCwd();
        } else {
            $baseDir = $_SERVER['argv'][1];
        }

        return realpath($baseDir);
    }

    public function getTestNames() : array
    {
        $settings = $this->getSettings();
        return array_keys((array) $settings['tests']);
    }

    public function getToIgnore() : array
    {
        $settings = $this->getSettings();
        if (isset($settings['settings']['ignore'])) {
            return $settings['settings']['ignore'];
        } else {
            return array();
        }
    }

    public function hasTestOption(string $testName, string $option) : bool
    {
        $settings = $this->getSettings();

        if (!isset($settings['tests'][$testName])) {
            return false;
        }
        if (in_array($option, $settings['tests'][$testName])) {
            return true;
        }

        return false;
    }

    public function getComposerBinDir() : string
    {
        $dir = __DIR__ . DIRECTORY_SEPARATOR;
        while (!($binDir = realpath($dir . 'vendor' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR))) {
            $dir .= '..' . DIRECTORY_SEPARATOR;
        }

        return $binDir;
    }
}
