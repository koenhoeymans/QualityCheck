<?php

namespace QualityCheck;

abstract class CommandBuilder
{
    protected $projectDir;

    protected $ignoreDirs = array();

    protected $ignoreFiles = array();

    public function setProjectDir(string $dir) : void
    {
        $this->projectDir = $dir;
    }

    public function ignoreDir(string $dir) : void
    {
        $this->ignoreDirs[] = $dir;
    }

    public function ignoreFile(string $file) : void
    {
        $this->ignoreFiles[] = $file;
    }
}
