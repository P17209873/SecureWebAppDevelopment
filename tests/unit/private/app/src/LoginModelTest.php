<?php


use secureWebAppCoursework\LoginModel;
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    public function testInValidCheckUserID() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();
        //hard coded pdo settings, not a good way and insecure
        $settings = [
            'rdbms' => 'mysql',
            'host' => 'localhost',//'mysql.tech.dmu.ac.uk',
            'db_name' => 'swadcoursework',//'p17204157',
            'port' => '3306',
            'user_name' => 'coursework',//'p17204157',
            'user_password' => 'password',//'taXes+86',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];
        $loginModel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $loginModel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $loginModel->setDatabaseConnectionSettings($settings);

        $fakeUserId = 99;

        $this->assertEquals(false, $loginModel -> checkUserID($fakeUserId));
    }

    //This test will result in an error unless a user exists with userId of 1
    public function testValidCheckUserID() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();
        //hard coded pdo settings, not a good way and insecure
        $settings = [
            'rdbms' => 'mysql',
            'host' => 'localhost',//'mysql.tech.dmu.ac.uk',
            'db_name' => 'swadcoursework',//'p17204157',
            'port' => '3306',
            'user_name' => 'coursework',//'p17204157',
            'user_password' => 'password',//'taXes+86',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];
        $loginModel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $loginModel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $loginModel->setDatabaseConnectionSettings($settings);

        $fakeUserId = 1;

        $this->assertEquals(false, $loginModel -> checkUserID($fakeUserId));
    }

    //This test will result in an error unless a user exists with userId of 1
    public function testValidcheckUserPassword() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();
        //hard coded pdo settings, not a good way and insecure
        $settings = [
            'rdbms' => 'mysql',
            'host' => 'localhost',//'mysql.tech.dmu.ac.uk',
            'db_name' => 'swadcoursework',//'p17204157',
            'port' => '3306',
            'user_name' => 'coursework',//'p17204157',
            'user_password' => 'password',//'taXes+86',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];
        $loginModel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $loginModel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $loginModel->setDatabaseConnectionSettings($settings);

        $userId = 1;
        $userPassword = 'password';
        $this->assertEquals(false, $loginModel->checkUserPassword($userId, $userPassword));
    }

    public function testInValidcheckUserPassword() {
        $loginModel = new \SecureWebAppCoursework\LoginModel();
        //hard coded pdo settings, not a good way and insecure
        $settings = [
            'rdbms' => 'mysql',
            'host' => 'localhost',//'mysql.tech.dmu.ac.uk',
            'db_name' => 'swadcoursework',//'p17204157',
            'port' => '3306',
            'user_name' => 'coursework',//'p17204157',
            'user_password' => 'password',//'taXes+86',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ];
        $loginModel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $loginModel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $loginModel->setDatabaseConnectionSettings($settings);

        $userId = 1;
        $fakeUserPassword = 'fakePassword';

        $this->assertEquals(false, $loginModel->checkUserPassword($userId, $fakeUserPassword));
    }
}
