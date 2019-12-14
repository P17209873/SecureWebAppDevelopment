<?php


use secureWebAppCoursework\LoginModel;
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    /*public function testFalseCheckUserID() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();
        $databaseWrapper = new \SecureWebAppCoursework\DatabaseWrapper();

        $loginModel -> setDatabaseWrapper($databaseWrapper);
        $loginModel -> setDatabaseConnectionSettings();
        $loginModel -> setSqlQueries();

        $this->assertEquals(true, $loginModel -> checkUserID('debug'));
    }*/

    public function testAttemptLogin() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();

        $this->assertEquals(true, $loginModel -> attemptLogin());
    }
}
