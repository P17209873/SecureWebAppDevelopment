<?php


use SecureWebAppCoursework\ProcessOutput;
use PHPUnit\Framework\TestCase;

class ProcessOutputTest extends TestCase
{
    public function testProcessOutput() {
        $processOutput = new \SecureWebAppCoursework\ProcessOutput();
        $html_output = 'This is a basic output';

        $this->assertEquals($html_output, $processOutput -> processOutput($html_output));
    }
}
