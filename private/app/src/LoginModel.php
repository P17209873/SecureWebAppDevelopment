<?php
/**
 * LoginModel.php
 *
 * LoginModel class that performs login functions
 */

namespace SecureWebAppCoursework;

/**
 * Data model that deals with logging into and logging out of the application
 *
 * Class LoginModel
 * @package SecureWebAppCoursework
 */
class LoginModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    public function __construct(){}

    public function __destruct(){}

    /**
     * Sets the database wrapper using parameters. In all cases the database wrapper is accessed from the
     * object stored in the application container
     *
     * @param $database_wrapper
     */
    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    /**
     * Sets the database connection settings using parameters. In all cases the database connection settings are
     * accessed from the object stored in the application container
     *
     * @param $database_connection_settings
     */
    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }

    /**
     * Sets the SQL queries using parameters. In all cases the SQL queries are access from the object stored
     * in the application container
     *
     * @param $sql_queries
     */
    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    /**
     * Stores the login attempt in the database.
     *
     * @param $userid
     * @param $login_result
     */
    public function storeLoginAttempt($userid, $login_result)
    {
        $query_string = $this->sql_queries->storeUserLoginLog();
        $query_params = array(':userid' => $userid, ':logincompleted' => $login_result);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $this->database_wrapper->safeQuery($query_string, $query_params);
    }

    /**
     * Retrieves the user password from the database
     *
     * @param $userid
     * @param $username
     * @return string
     */
    public function checkUserPassword($userid, $username)
    {
        $query_string = $this->sql_queries->checkUserPassword();
        $query_params = array(':userid' => $userid, ':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);


        if ($result == true)
        { // This signifies that there was a QUERY ERROR
            return 'Unfortunately Login was unable to connect.  Please try again later.';
        }
        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userpassword'];
        }
    }

    /**
     * Checks the user ID, calling the database query
     *
     * @param $username
     * @return string
     */
    public function checkUserID($username)
    {
        $query_string = $this->sql_queries->getUserID();
        $query_params = array(':userusername' => $username);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);

        if ($result == true)
        { // This signifies that there was a QUERY ERROR
            return 'Unfortunately Login was unable to connect.  Please try again later.';
        }
        else
        {
            $result = $this->database_wrapper->safeFetchArray();
            return $result['userid'];
        }
    }
}
