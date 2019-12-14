<?php


use SecureWebAppCoursework\SQLQueries;
use PHPUnit\Framework\TestCase;

class SQLQueriesTest extends TestCase
{
    public function testStoreUserLoginLog() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string  = "INSERT INTO UserLoginLogs SET UserID = :User_ID LoginCompleted = :LoginCompleted";

        $this->assertEquals($query_string, $SQLQueries -> storeUserLoginLog());
    }

    public function testCreateNewUser() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "INSERT INTO Users SET UserUsername = :UserUsername, UserPassword = :UserPassword, UserEmail = :UserEmail, UserFirstName = :UserFirstName, UserLastName = :UserLastName";

        $this->assertEquals($query_string, $SQLQueries -> createNewUser());
    }

    public function testGetUserID() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "SELECT UserID FROM Users WHERE UserUsername = :UserUsername";

        $this->assertEquals($query_string, $SQLQueries -> getUserID());
    }

    public function testGetUserEmail() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "SELECT UserID FROM Users WHERE UserEmail = :UserEmail";

        $this->assertEquals($query_string, $SQLQueries -> getUserEmail());
    }
}
