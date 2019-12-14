<?php


use secureWebAppCoursework\RegistrationModel;
use PHPUnit\Framework\TestCase;

class RegistrationModelTest extends TestCase
{
    public function testCreateNewUser() {
        $registrationModel = new \SecureWebAppCoursework\RegistrationModel();

        $this->assertEquals(false, true);
    }
}
