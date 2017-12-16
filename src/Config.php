<?php

namespace QualityCheck;

interface Config
{
    public function getBuildDir() : string;

    public function getProjectDir() : string;

    public function getTestNames() : array;

    public function getToIgnore() : array;

    public function hasTestOption(string $testName, string $option) : bool;
}
