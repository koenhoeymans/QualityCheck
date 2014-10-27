<?php

namespace QualityCheck;

abstract class CommandBuilder
{
    protected $projectDir;

    protected $ignoreDirs = array();

    protected $ignoreFiles = array();

    public function setProjectDir($dir)
    {
        $this->projectDir = $dir;
    }

    public function ignoreDir($dir)
    {
        $this->ignoreDirs[] = $dir;
    }

    public function ignoreFile($file)
    {
        $this->ignoreFiles[] = $file;
    }
}
