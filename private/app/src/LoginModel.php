<?php

namespace secureWebAppCoursework;

/**
 * Class LoginModel
 * @package secureWebAppCoursework
 *
 * Data model that deals with logging into and logging out of the application
 */
class LoginModel
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

    public function storeLoginAttempt($user_id, $login_result)
    {
        $query_string = $this->sql_queries->storeUserLoginLog();
        $query_params = array(':User_ID' => $user_id, ':LoginCompleted' => $login_result);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string, $query_params);
    }

    public function attemptLogin()
    {
        return true;
    }

    public function checkUserID($username)
    {
        $query_string = $this->sql_queries->getUserID();
        $query_params = array(':UserUsername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if($result == true) // This signifies that there was a QUERY ERROR
        {
            return 'Unfortunately there has been a query error';
        }

        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['UserID'];
        }

        var_dump($result); //TODO: remove - testing purposes (
    }
}