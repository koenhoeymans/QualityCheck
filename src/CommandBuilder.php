<?php

namespace QualityCheck;

abstract class CommandBuilder
{
    protected $projectDir;

    protected $ignoreDirs = array();

    protected $ignoreFiles = array();

    public function setProjectDir(string $dir)
    {
        $this->projectDir = $dir;
    }

    public function ignoreDir(string $dir)
    {
        $this->ignoreDirs[] = $dir;
    }

    public function ignoreFile(string $file)
    {
        $this->ignoreFiles[] = $file;
    }
}
