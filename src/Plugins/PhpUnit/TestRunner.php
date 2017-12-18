<?php

namespace QualityCheck\Plugins\PhpUnit;

class TestRunner
{
    private $config;

    private $outputDir;

    private $coverageDir;

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results) : void
    {
        $this->outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpunit' . DIRECTORY_SEPARATOR;
        $this->coverageDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'codecoverage' . DIRECTORY_SEPARATOR;
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }

        $cwd = getCwd();
        chdir($this->config->getProjectDir());
        $log = shell_exec($this->getCommand());
        chdir($cwd);

        file_put_contents($this->outputDir . 'cmdLog.txt', $log);

        $results->addLogFile('PhpUnit log', 'phpunit/cmdLog.txt');
        if ($this->config->hasTestOption('phpunit', 'codecoverage')) {
            $results->addLogFile('CodeCoverage', 'codecoverage/index.html');
        }
    }

    private function getCommand() : string
    {
        $cmd = $this->config->getComposerBinDir() . 'phpunit';

        $cmd = $this->addCoverageOption($cmd);

        return $cmd;
    }

    private function addCoverageOption($cmd) : string
    {
        if ($this->config->hasTestOption('phpunit', 'codecoverage')) {
            $cmd .= ' --coverage-html ' . $this->coverageDir;
        }

        return $cmd;
    }
}
