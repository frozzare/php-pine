<?php

use PHPUnit\Framework\TestCase;

class PineTest extends TestCase
{
    public function setUp()
    {
        $this->pine = pine();
    }

    public function tearDown()
    {
        unset($this->pine);
    }

    public function testFailRun()
    {
        $output = $this->pine->run('test');

        $this->assertFalse($output);
    }

    public function testSuccessRun()
    {
        $output = $this->pine->file(__DIR__ . '/testdata/Pinefile.php')->run('build');

        $this->assertTrue($output);
        $this->expectOutputRegex('/Building.../');
    }

    public function testBeforeRun()
    {
        $output = $this->pine->file(__DIR__ . '/testdata/Pinefile.php')->run('build:site');

        $this->assertTrue($output);
        $this->expectOutputRegex('/Building.../');
        $this->expectOutputRegex('/Building site.../');
    }

    public function testAfterRun()
    {
        $output = $this->pine->file(__DIR__ . '/testdata/Pinefile.php')->run('build:site');

        $this->assertTrue($output);
        $this->expectOutputRegex('/Building.../');
        $this->expectOutputRegex('/Building site.../');
        $this->expectOutputRegex('/Building done.../');
    }
}
