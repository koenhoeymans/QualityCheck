<?php

namespace QualityCheck;

class TestUtils extends \PHPUnit_Framework_TestCase
{
    protected function removeDir($dir)
    {
        if (!$dir || !is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            is_dir("$dir/$file")
                ? $this->removeDir("$dir/$file")
                : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
