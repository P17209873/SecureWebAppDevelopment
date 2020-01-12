<?php


use secureWebAppCoursework\RegistrationModel;
use PHPUnit\Framework\TestCase;

class RegistrationModelTest extends TestCase
{
    //This test does create a user in database with these credentials
    public function testValidCreateNewUser() {
        $registrationodel = new \SecureWebAppCoursework\RegistrationModel();

        //hard coded pdo settings, not a good way
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
        $registrationodel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $registrationodel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $registrationodel->setDatabaseConnectionSettings($settings);

        $this->assertEquals(true, $registrationodel -> createNewUser('fakeUsername', 'fakeHashedPassword', 'fakeFirstName', 'fakeSurname', 'fakeEmail'));
    }

    public function testValidDoesUserNameExist() {
        $registrationodel = new \SecureWebAppCoursework\RegistrationModel();

        //hard coded pdo settings, not a good way
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
        $registrationodel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $registrationodel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $registrationodel->setDatabaseConnectionSettings($settings);

        $this->assertEquals(false, $registrationodel -> doesUsernameExist('NewfakeUsername'));
    }

    public function testValidDoesEmailExist() {
        $registrationodel = new \SecureWebAppCoursework\RegistrationModel();

        //hard coded pdo settings, not a good way
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
        $registrationodel->setSqlQueries(new \SecureWebAppCoursework\SQLQueries());
        $registrationodel->setDatabaseWrapper(new \SecureWebAppCoursework\DatabaseWrapper());
        $registrationodel->setDatabaseConnectionSettings($settings);

        $this->assertEquals(false, $registrationodel -> doesUsernameExist('Newfakeemail@gmail.com'));
    }
}
