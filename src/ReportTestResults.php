<?php

namespace QualityCheck;

class ReportTestResults implements \Epa\Api\Event
{
    private $logFiles = array();

    /**
     * Add the relative url to a log file.
     *
     * @param string $name
     * @param string $link
     */
    public function addLogFile(string $name, string $link)
    {
        $this->logFiles[$name] = $link;
    }

    public function getLogFiles()
    {
        return $this->logFiles;
    }
}
