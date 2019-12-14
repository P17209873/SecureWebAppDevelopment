<?php

/**
 * SQLQueries.php
 *
 * hosts all SQL queries to be used by the Model
 */

namespace secureWebAppCoursework;

class SQLQueries
{
    public function __construct() {}

    public function __destruct() {}

    //TODO: Add necessary queries here!
    //TODO: Add Docblock comments to query methods

    /**
     * This query inserts login attempts into the UserLoginLogs table, taking the UserID and LoginCompleted values to store every attempt,
     * and whether the attempt succeeded or failed.
     *
     * @return string <- returns the query string necessary for the application to take and use
     */
    public function storeUserLoginLog()
    {
        $query_string  = "INSERT INTO UserLoginLogs ";
        $query_string .= "SET ";
        $query_string .= "UserID = :User_ID ";
        $query_string .= "LoginCompleted = :LoginCompleted";
        return $query_string;
    }

    /**
     * This query adds new users to the database from the registration form.
     *
     * @return string <- returns the query string necessary for the application to take and use
     */
    public function createNewUser()
    {
        $query_string = "INSERT INTO Users ";
        $query_string .= "SET ";
        $query_string .= "UserUsername = :UserUsername, ";
        $query_string .= "UserPassword = :UserPassword, ";
        $query_string .= "UserEmail = :UserEmail, ";
        $query_string .= "UserFirstName = :UserFirstName, ";
        $query_string .= "UserLastName = :UserLastName";

        return $query_string;
    }

    /**
     * This query checks the database to see whether a user already exists. It is used by the login (authenticate) route to check that the entered username exists,
     * and by the registration (registeruser) route to prevent multiple users from having the same username.
     *
     * @return string
     */
    public function getUserID()
    {
        $query_string = "SELECT UserID FROM Users ";
        $query_string .= "WHERE UserUsername = :UserUsername";

        return $query_string;
    }

    /**
     * This query checks the database to see whether a user entered email address already exists. It is used by the login (authenticate) route to check that the entered username exists,
     * and by the registration (registeruser) route to prevent multiple users from having the same username.
     *
     * @return string
     */
    public function getUserEmail(){
        $query_string = "SELECT UserID FROM Users ";
        $query_string .= "WHERE UserEmail = :UserEmail";

        return $query_string;
    }
}
