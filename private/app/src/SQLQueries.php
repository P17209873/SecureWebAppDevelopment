<?php

namespace SecureWebAppCoursework;

/**
 * SQLQueries.php
 *
 * hosts all SQL queries to be used by the data models
 */
class SQLQueries
{
    public function __construct()
    {
    }

    public function __destruct()
    {
    }

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
        $query_string  = "INSERT INTO userloginlogs ";
        $query_string .= "SET ";
        $query_string .= "userid = :userid, ";
        $query_string .= "logincompleted = :logincompleted";
        return $query_string;
    }

    /**
     * This query adds new users to the database from the registration form.
     *
     * @return string <- returns the query string necessary for the application to take and use
     */
    public function createNewUser()
    {
        $query_string = "INSERT INTO users ";
        $query_string .= "SET ";
        $query_string .= "userusername = :userusername, ";
        $query_string .= "userpassword = :userpassword, ";
        $query_string .= "useremail = :useremail, ";
        $query_string .= "userfirstname = :userfirstname, ";
        $query_string .= "userlastname = :userlastname";

        return $query_string;
    }

    /**
     * @return string
     */
    public function checkUserPassword()
    {
        $query_string = "SELECT userid, userusername, userpassword ";
        $query_string .= "FROM users ";
        $query_string .= "WHERE ";
        $query_string .= "userid = :userid AND ";
        $query_string .= "userusername = :userusername";

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
        $query_string = "SELECT userid FROM users ";
        $query_string .= "WHERE userusername = :userusername";

        return $query_string;
    }

    /**
     * This query checks the database to see whether a user entered email address already exists. It is used by the login (authenticate) route to check that the entered username exists,
     * and by the registration (registeruser) route to prevent multiple users from having the same username.
     *
     * @return string
     */
    public function getUserEmail()
    {
        $query_string = "SELECT userid FROM users ";
        $query_string .= "WHERE useremail = :useremail";

        return $query_string;
    }


    /**
     * This query inserts the circuitboardstates values into the table. This table stores the message values.
     *
     * @return string
     */
    public function insertIntoCircuitBoardStates()
    {
        $query_string = "INSERT INTO circuitboardstates ";
        $query_string .= "SET ";
        $query_string .= "switch01state = :switch01state, ";
        $query_string .= "switch02state = :switch02state, ";
        $query_string .= "switch03state = :switch03state, ";
        $query_string .= "switch04state = :switch04state, ";
        $query_string .= "fanstate = :fanstate, ";
        $query_string .= "heatertemperature = :heatertemperature, ";
        $query_string .= "keypadvalue = :keypadvalue";

        return $query_string;
    }

    /**
     * This query inserts the retrievedmessages values into the table. This table stores the message metadata.
     *
     * @return string
     */
    public function insertIntoRetrievedMessages()
    {
        $query_string = "INSERT INTO retrievedmessages ";
        $query_string .= "SET ";
        $query_string .= "messagesentto = :messagesentto, ";
        $query_string .= "messagesentfrom = :messagesentfrom, ";
        $query_string .= "receivedtime = :receivedtime, ";
        $query_string .= "bearer = :bearer, ";
        $query_string .= "messageref = :messageref, ";
        $query_string .= "stateid = :stateid";

        return $query_string;
    }

    /**
     * This query retrieves the retrievedmessages and circuitboardstate values, stitching them together using an Inner Join
     *
     * @return string
     */
    public function retrieveMessagesFromDB()
    {
        $query_string = "SELECT retrievedmessages.retrievedmessageid, retrievedmessages.messagesentto, retrievedmessages.messagesentfrom, ";
        $query_string .= "retrievedmessages.receivedtime, retrievedmessages.bearer, retrievedmessages.messageref    , retrievedmessages.stateid, ";
        $query_string .= "circuitboardstates.stateid, circuitboardstates.switch01state, circuitboardstates.switch02state, circuitboardstates.switch03state, circuitboardstates.switch04state, ";
        $query_string .= "circuitboardstates.fanstate, circuitboardstates.heatertemperature, circuitboardstates.keypadvalue ";
        $query_string .= "FROM retrievedmessages INNER JOIN circuitboardstates ON retrievedmessages.stateid = circuitboardstates.stateid";

        return $query_string;
    }
}
