<?php

namespace QualityCheck\Plugins\PhpCs;

class TestRunner
{
    private $config;

    private $outputDir;

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results)
    {
        $this->outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpcs' . DIRECTORY_SEPARATOR;
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }

        $log = shell_exec($this->getCommand());
        if (empty($log)) {
            $log = 'no problems detected';
        }
        file_put_contents($this->outputDir . 'cmdLog.txt', $log);

        $results->addLogFile(
            'PhpCodeSniffer PSR2 log',
            $this->outputDir . 'cmdLog.txt'
        );
    }

    private function getCommand()
    {
        $cmd = realpath(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpcs'
        );

        $exclude = implode($this->config->getToIgnore(), ',');

        if (!empty($exclude)) {
            $cmd .= ' --ignore=' . $exclude;
        }

        $cmd .= ' --extensions=php --standard=PSR2 ' . $this->config->getProjectDir();

        return $cmd;
    }
}
