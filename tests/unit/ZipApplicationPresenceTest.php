<?php


class ZipApplicationPresenceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testZipApplicationPresence()
    {
        $output = shell_exec('zip --help');

        $this->assertContains('Copyright (c) 1990-2008 Info-ZIP', $output);
    }
}