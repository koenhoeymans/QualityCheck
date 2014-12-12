<?php

namespace QualityCheck\Plugins\PhpCpd;

class TestRunner
{
    private $config;

    private $ignoreFiles = array();

    private $ignoreDirs = array();

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results)
    {
        $outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDir)) {
            mkdir($outputDir);
        }

        $this->addToIgnore();

        $log = shell_exec($this->getCommand());

        file_put_contents($outputDir . 'cmdLog.txt', $log);

        $results->addLogFile('PhpCpd log', 'phpcpd/cmdLog.txt');
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
        $bins = array(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpcpd',
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpcpd',
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
