<?php

namespace QualityCheck;

class QualityCheck
{
    use \Epa\Api\ObserverStore;

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function build()
    {
        $buildDir = $this->config->getBuildDir();

        if (!is_dir($buildDir)) {
            mkdir($this->config->getBuildDir());
        }

        $reportTestResults = new ReportTestResults();
        $this->notify($reportTestResults);

        $contents = '<html><head><title>overview</title></head><body>';
        foreach ($reportTestResults->getLogFiles() as $name => $file) {
            $contents .= "<div><a href='" . $file . "'>" . $name . "</a>";
        }
        $contents .= '</body></html>';
        file_put_contents(
            $buildDir . DIRECTORY_SEPARATOR . 'index.html',
            $contents
        );
    }
}
