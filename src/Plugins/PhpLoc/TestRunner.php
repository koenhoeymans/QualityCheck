<?php

namespace QualityCheck\Plugins\PhpLoc;

class TestRunner
{
    private $config;

    private $outputDir;

    private $ignoreDirs = array();

    private $ignoreFiles = array();

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results) : void
    {
        $this->createOutputDir();
        $this->addToIgnore();

        $log = shell_exec($this->getCommand());

        file_put_contents($this->outputDir . 'cmdLog.txt', $log);
        $results->addLogFile('PhpLoc log', 'phploc/cmdLog.txt');
    }

    private function addToIgnore() : void
    {
        foreach ($this->config->getToIgnore() as $fileOrDir) {
            if (is_dir($fileOrDir)) {
                $this->ignoreDirs[] = $fileOrDir;
            } else {
                $this->ignoreFile[] = $fileOrDir;
            }
        }
    }

    private function createOutputDir() : void
    {
        $this->outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phploc' . DIRECTORY_SEPARATOR;

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }
    }

    private function getCommand() : string
    {
        $bins = array(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phploc',
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phploc',
        );

        foreach ($bins as $bin) {
            $cmd = realpath($bin);
            if ($cmd) {
                break;
            }
        }

        $excludeFiles = implode($this->ignoreFiles, ',');
        if (!empty($excludeFiles)) {
            $cmd .= ' --names-exclude=' . $excludeFiles;
        }

        foreach ($this->ignoreDirs as $dir) {
            $cmd .= ' --exclude=' . $dir;
        }

        $cmd .= ' ' . $this->config->getProjectDir();

        return $cmd;
    }
}
