<?php


use SecureWebAppCoursework\DatabaseWrapper;
use PHPUnit\Framework\TestCase;

class DatabaseWrapperTest extends TestCase
{
    public function testValidMakeDatabaseConnection() {
        $databaseWrapper = new \SecureWebAppCoursework\DatabaseWrapper();

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
        $databaseWrapper->setDatabaseConnectionSettings($settings);

        $this->assertEquals(false, $databaseWrapper -> makeDatabaseConnection());
    }
}
