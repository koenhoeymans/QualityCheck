<?php

namespace QualityCheck\Plugins\PhpCs;

class TestRunner
{
    private $config;

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results) : void
    {
        $outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpcs' . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDir)) {
            mkdir($outputDir);
        }

        $log = shell_exec($this->getCommand());
        if (empty($log)) {
            $log = 'no problems detected';
        }
        file_put_contents($outputDir . 'cmdLog.txt', $log);

        $results->addLogFile('PhpCodeSniffer PSR2 log', $outputDir . 'cmdLog.txt');
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
            . 'phpcs',
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpcs',
        );

        foreach ($bins as $bin) {
            $cmd = realpath($bin);
            if ($cmd) {
                break;
            }
        }

        $exclude = implode($this->config->getToIgnore(), ',');

        if (!empty($exclude)) {
            $cmd .= ' --ignore=' . $exclude;
        }

        $cmd .= ' --extensions=php --standard=PSR2 ' . $this->config->getProjectDir();

        return $cmd;
    }
}
