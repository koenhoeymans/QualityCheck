<?php

namespace QualityCheck\Plugins\PhpMd;

class TestRunner
{
    private $config;

    private $outputDir;

    public function __construct(\QualityCheck\Config $config)
    {
        $this->config = $config;
    }

    public function reportTestResults(\QualityCheck\ReportTestResults $results) : void
    {
        $this->createOutputDir();

        $log = shell_exec($this->getCommand());

        file_put_contents($this->outputDir . 'cmdLog.txt', $log);
        $results->addLogFile('PhpMd log', $this->outputDir . 'result.html');
    }

    private function createOutputDir() : void
    {
        $this->outputDir = $this->config->getBuildDir() . DIRECTORY_SEPARATOR
            . 'phpmd' . DIRECTORY_SEPARATOR;

        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir);
        }
    }

    private function getCommand() : string
    {
        $cmd = $this->config->getComposerBinDir() . 'phpmd';
        $cmd .= ' ' . $this->config->getProjectDir()
            . ' html cleancode,codesize,controversial,design,naming,unusedcode';

        $excluded = $this->config->getToIgnore();
        foreach ($excluded as $key => $value) {
            $excluded[$key] = preg_quote($value);
        }
        if (!empty($excluded)) {
            $excluded = implode($excluded, ',');
            $cmd .= ' --exclude ' . $excluded;
        }

        $cmd .= ' --reportfile ' . $this->outputDir . 'result.html';

        return $cmd;
    }
}
