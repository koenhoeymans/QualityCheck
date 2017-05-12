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

    public function reportTestResults(\QualityCheck\ReportTestResults $results)
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

    private function getCommand()
    {
        $bins = array(
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpunit',
            __DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR
            . 'vendor' . DIRECTORY_SEPARATOR
            . 'bin' . DIRECTORY_SEPARATOR
            . 'phpunit',
        );

        foreach ($bins as $bin) {
            $cmd = realpath($bin);
            if ($cmd) {
                break;
            }
        }

        $cmd = $this->addCoverageOption($cmd);

        return $cmd;
    }

    private function addCoverageOption($cmd)
    {
        if ($this->config->hasTestOption('phpunit', 'codecoverage')) {
            $cmd .= ' --coverage-html ' . $this->coverageDir;
        }

        return $cmd;
    }
}
