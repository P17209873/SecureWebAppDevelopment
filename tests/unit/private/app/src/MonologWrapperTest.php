<?php


use secureWebAppCoursework\MonologWrapper;
use PHPUnit\Framework\TestCase;

class MonologWrapperTest extends TestCase
{
    public function testSetDebug() {
        $monologWrapper = new \SecureWebAppCoursework\MonologWrapper();

        $this->assertEquals(true, $monologWrapper -> setLogType('debug'));
    }

    // Tests there are no errors
    public function testAddLogMessage() {
        $monologWrapper = new \SecureWebAppCoursework\MonologWrapper();

        $this->assertEquals(false, $monologWrapper->addLogMessage('test debug message', 'debug'));

    }
}
