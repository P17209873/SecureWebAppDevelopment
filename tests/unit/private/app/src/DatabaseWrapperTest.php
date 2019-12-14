<?php


use SecureWebAppCoursework\DatabaseWrapper;
use PHPUnit\Framework\TestCase;

// USE "php vendor/bin/phpunit" in cmd to run tests
class DatabaseWrapperTest extends TestCase
{
    public function testdbConnection() {
        $dbWrapper = new \SecureWebAppCoursework\DatabaseWrapper();

        $this->assertEquals(false, true);
    }
}
