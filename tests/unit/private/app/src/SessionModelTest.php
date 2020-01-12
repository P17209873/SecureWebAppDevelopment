<?php


use SecureWebAppCoursework\SessionModel;
use PHPUnit\Framework\TestCase;

class SessionModelTest extends TestCase
{
    public function testStoreDataInSessionDatabase() {
        $sessionModel = new \SecureWebAppCoursework\SessionModel();

        $this->assertEquals(false, $sessionModel -> storeDataInSessionDatabase());
    }
}
