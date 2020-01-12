<?php


use SecureWebAppCoursework\SQLQueries;
use PHPUnit\Framework\TestCase;

class SQLQueriesTest extends TestCase
{
    public function testStoreUserLoginLog() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string  = "INSERT INTO userloginlogs SET userid = :userid, logincompleted = :logincompleted";

        $this->assertEquals($query_string, $SQLQueries -> storeUserLoginLog());
    }

    public function testCreateNewUser() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "INSERT INTO users SET userusername = :userusername, userpassword = :userpassword, useremail = :useremail, userfirstname = :userfirstname, userlastname = :userlastname";

        $this->assertEquals($query_string, $SQLQueries -> createNewUser());
    }

    public function testGetUserID() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "SELECT userid FROM users WHERE userusername = :userusername";

        $this->assertEquals($query_string, $SQLQueries -> getUserID());
    }

    public function testGetUserEmail() {
        $SQLQueries = new \SecureWebAppCoursework\SQLQueries();

        $query_string = "SELECT userid FROM users WHERE useremail = :useremail";

        $this->assertEquals($query_string, $SQLQueries -> getUserEmail());
    }
}
