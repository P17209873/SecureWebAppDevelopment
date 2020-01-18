<?php

namespace SecureWebAppCoursework;

/**
 * Class RegistrationModel
 * @package SecureWebAppCoursework
 *
 * Data model that deals with registering new users of the application
 */
class RegistrationModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct(){}

    public function __destruct(){}

    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    /**
     * Performs the SQL Query necessary to insert newly created user data into database.
     *
     * @param $cleaned_username
     * @param $hashed_password
     * @param $cleaned_firstname
     * @param $cleaned_lastname
     * @param $cleaned_email
     */
    public function createNewUser($cleaned_username, $hashed_password, $cleaned_firstname, $cleaned_lastname, $cleaned_email)  : bool
    {
        $query_string = $this->sql_queries->createNewUser();

        $query_params = array(':userusername' => $cleaned_username, ':userpassword' => $hashed_password,
            ':useremail' => $cleaned_email, ':userfirstname' => $cleaned_firstname,
            ':userlastname' => $cleaned_lastname);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == false)
        {
            return true;
        }

        else
        {
            return false;
        }
    }

    /**
     * Checks the database to see whether the username passed in already exists.
     *
     * @param $username
     * @return bool|string <- string for error, bool for successfully performed query (true indicates that a user exists)
     */
    public function doesUsernameExist($username)
    {
        $query_string = $this->sql_queries->getUserId();
        $query_params = array(':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true)
        {
            return 'Unfortunately there has been a query error';
        }

        else
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null)
            {
                return true;
            }

            else
            {
                return false;
            }
        }
    }

    /**
     * Checks the database to see whether the email passed in already exists.
     *
     * @param $username
     * @return bool|string <- string for error, bool for successfully performed query (true indicates that an email exists)
     */
    public function doesEmailExist($email)
    {
        $query_string = $this->sql_queries->getUserEmail();
        $query_params = array(':useremail' => $email);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true)
        {
            return 'Unfortunately there has been a query error';
        }

        else
        {
            $result = $this->database_wrapper->safeFetchArray();

            if($result != null)
            {
                return true;
            }

            else
            {
                return false;
            }
        }
    }
}