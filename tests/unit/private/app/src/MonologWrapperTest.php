<?php


use secureWebAppCoursework\MonologWrapper;
use PHPUnit\Framework\TestCase;

class MonologWrapperTest extends TestCase
{
    public function testSetDebug() {
        $monologWrapper = new \SecureWebAppCoursework\MonologWrapper();
        var_dump($monologWrapper -> setLogType('debug'));

        $this->assertEquals(true, $monologWrapper -> setLogType('debug'));
    }
}
