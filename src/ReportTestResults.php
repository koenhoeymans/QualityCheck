<?php

namespace QualityCheck;

class ReportTestResults implements \Epa\Api\Event
{
    private $logFiles = array();

    public function addLogFile($name, $link)
    {
        $this->logFiles[$name] = $link;
    }

    public function getLogFiles()
    {
        return $this->logFiles;
    }
}
