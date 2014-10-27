<?php

namespace QualityCheck;

interface Config
{
    public function getBuildDir();

    public function getProjectDir();

    public function getTestNames();

    public function getToIgnore();

    public function hasTestOption($testName, $option);
}
