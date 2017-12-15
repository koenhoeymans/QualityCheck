<?php

namespace QualityCheck;

class UseTest extends TestUtils
{
    private $buildDir;

    public function setup()
    {
        $this->buildDir = __DIR__ . DIRECTORY_SEPARATOR
            . 'SampleProject' . DIRECTORY_SEPARATOR . 'tmp';

        if (is_dir($this->buildDir)) {
            $this->removeDir($this->buildDir);
        }
    }

    public function teardown()
    {
        if (is_dir($this->buildDir)) {
            $this->removeDir($this->buildDir);
        }
    }

    /**
     * @test
     */
    public function basicUsage()
    {
        $cwd = getCwd();
        chdir(__DIR__ . DIRECTORY_SEPARATOR . 'SampleProject');

        $bin = __DIR__ . DIRECTORY_SEPARATOR
            . '..'     . DIRECTORY_SEPARATOR
            . '..'     . DIRECTORY_SEPARATOR
            . 'src' . DIRECTORY_SEPARATOR
            . 'qc';
        $project = __DIR__ . DIRECTORY_SEPARATOR  . 'SampleProject';

        passthru($bin . ' ' . $project);

        chdir($cwd);

        $this->createsBuildDir();
        $this->doesPhpUnitTests();
        $this->addsCodeCoverage();
        $this->doesPhpLocTests();
        $this->doesPsr2Test();
        $this->doesCopyPasteDetectorTest();
        $this->createsOverviewOfTestRuns();
        $this->appliesGlobalIgnoreRuleToPhpCpd();
        $this->doesPhpMessDetectorTest();
    }

    private function createsBuildDir()
    {
        $this->assertTrue(is_dir($this->buildDir), 'no build dir created');
    }

    private function doesPhpUnitTests()
    {
        $phpUnitDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpunit' . DIRECTORY_SEPARATOR;

        $this->assertTrue(
            file_exists($phpUnitDir . 'cmdLog.txt'),
            'PHPUnit log file does not exist'
        );

        $this->assertContains(
            'Assertions',
            file_get_contents($phpUnitDir . 'cmdLog.txt')
        );
    }

    private function addsCodeCoverage()
    {
        $codeCoverageDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'codecoverage' . DIRECTORY_SEPARATOR;

        $this->assertContains(
            'Code Coverage',
            file_get_contents($codeCoverageDir . 'index.html')
        );
    }

    private function doesPhpLocTests()
    {
        $phpUnitDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phploc' . DIRECTORY_SEPARATOR;

        $this->assertTrue(
            file_exists($phpUnitDir . 'cmdLog.txt'),
            'PHPLoc log file does not exist'
        );

        $this->assertContains(
            'Lines of Code',
            file_get_contents($phpUnitDir . 'cmdLog.txt')
        );
    }

    private function doesPsr2Test()
    {
        $phpCsDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpcs' . DIRECTORY_SEPARATOR;

        $this->assertTrue(
            file_exists($phpCsDir . 'cmdLog.txt'),
            'PHPCS log file does not exist'
        );

        $this->assertContains(
            'Opening brace',
            file_get_contents($phpCsDir . 'cmdLog.txt')
        );
    }

    private function doesCopyPasteDetectorTest()
    {
        $phpCpdDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR;

        $this->assertTrue(
            file_exists($phpCpdDir . 'cmdLog.txt'),
            'phpcpd log file does not exist'
        );

        $this->assertContains(
            'duplicated lines',
            file_get_contents($phpCpdDir . 'cmdLog.txt')
        );
    }

    private function createsOverviewOfTestRuns()
    {
        $overviewFile = $this->buildDir . DIRECTORY_SEPARATOR . 'index.html';
        $this->assertTrue(
            file_exists($overviewFile),
            'overview file index.html does not exist'
        );
        $this->assertContains('PhpUnit', file_get_contents($overviewFile));
    }

    private function appliesGlobalIgnoreRuleToPhpCpd()
    {
        $phpCpdDir = $this->buildDir . DIRECTORY_SEPARATOR
            . 'phpcpd' . DIRECTORY_SEPARATOR;
        $log = file_get_contents($phpCpdDir . 'cmdLog.txt');

        // the ignored file bar.php contains duplication according to PHPCPD
        $this->assertContains('0.00% duplicated lines', $log);
    }

    private function doesPhpMessDetectorTest()
    {
        $phpmdReport = $this->buildDir . DIRECTORY_SEPARATOR . 'phpmd' .
            DIRECTORY_SEPARATOR . 'index.html';
        $this->assertTrue(
            file_exists($phpmdReport),
            'PHPMD file does not exist'
        );
        $this->assertContains('PHPMD', file_get_contents($overviewFile));
    }
}
