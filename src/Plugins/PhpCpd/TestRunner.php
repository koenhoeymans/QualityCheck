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

    public function reportTestResults(\QualityCheck\ReportTestResults $results) : void
    {
        $outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR;
        if (!is_dir($outputDir)) {
            mkdir($outputDir);
        }

        $log = shell_exec($this->getCommand());

        file_put_contents($outputDir . 'cmdLog.txt', $log);

        $results->addLogFile('PhpCpd log', 'phpcpd/cmdLog.txt');
    }

    public function getCommand() : string
    {
        $cmd = $this->config->getComposerBinDir() . 'phpcpd';

        $excluded = $this->config->getToIgnore();
        foreach ($excluded as $key => $value) {
            $excluded[$key] = preg_quote($value);
        }
        if (!empty($excluded)) {
            $excluded = '\'~' . implode($excluded, '|') . '~\'';
            $cmd .= ' --regexps-exclude ' . $excluded;
        }

        $cmd .= ' ' . $this->config->getProjectDir();

        return $cmd;
    }
}
