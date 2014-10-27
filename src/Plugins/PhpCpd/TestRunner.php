<?php

namespace QualityCheck\Plugins\PhpCpd;

class TestRunner
{
    private $config;

    private $outputDir;

    private $ignoreFiles = array();

    private $ignoreDirs = array();

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results)
    {
        $this->outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR;
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }

        $this->addToIgnore();

        $log = shell_exec($this->getCommand());

        file_put_contents($this->outputDir . 'cmdLog.txt', $log);

        $results->addLogFile('PhpCpd log', $this->outputDir . 'cmdLog.txt');
    }

    private function addToIgnore()
    {
        foreach ($this->config->getToIgnore() as $fileOrDir) {
            if (is_dir($fileOrDir)) {
                $this->ignoreDirs[] = $fileOrDir;
            } else {
                $this->ignoreFiles[] = $fileOrDir;
            }
        }
    }

    public function getCommand()
    {
        $cmd = realpath(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpcpd'
        );

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
