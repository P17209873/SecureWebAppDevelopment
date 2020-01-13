<?php
/**
 * SecureWebAppModel.php
 *
 * Acts as the data model for the SOAP Server
 */
namespace SecureWebAppCoursework;

/**
 * SecureWebAppModel.php
 *
 * This class acts as the Data Model of the application, containing all methods that implement and manage the data logic
 * required to access the M2M SOAP Service, alongside database logic necessary for updating the messages tables
 */
class SecureWebAppModel
{
    private $database_wrapper;
    private $database_connection_settings;
    private $sql_queries;

    private $xml_message;
    private $xml_parser;
    private $soap_wrapper;
    private $detail;
    private $msisdn;
    private $username;
    private $password;

    /**
     * SecureWebAppModel constructor that provides default username and password information for EE M2M Connect login purposes
     */
    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->xml_message = '';
        $this->msisdn = SYSTEM_MSISDN;
        $this->username = '19p17204157';
        $this->password = 'cameraN1nthchair';
    }

    public function __destruct(){}

    /**
     * @param $soap_wrapper
     *
     * Sets the SOAP Wrapper object per the parameter
     */
    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    //database functions

    /**
     * Sets the database wrapper for the SecureWebAppModel
     *
     * @param $database_wrapper
     */
    public function setDatabaseWrapper($database_wrapper)
    {
        $this->database_wrapper = $database_wrapper;
    }

    /**
     * Sets the database connection settings for the SecureWebAppModel
     *
     * @param $database_connection_settings
     */
    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }


    /**
     * Sets the SQL Queries for the SecureWebAppModel
     *
     * @param $sql_queries
     */
    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }

    /**
     * Executes the Insert Into CircuitBoardState Database query
     */

    public function insertIntoCircuitBoardStates($cleaned_switch1state, $cleaned_switch2state, $cleaned_switch3state, $cleaned_switch4state, $cleaned_fanstate, $cleaned_heatertemperature, $cleaned_keypadvalue)
    {
        $query_string = $this->sql_queries->insertIntoCircuitBoardStates();

        $query_params = array(':switch01state' => $cleaned_switch1state, ':switch02state' => $cleaned_switch2state, ':switch03state' => $cleaned_switch3state,
                              ':switch04state' => $cleaned_switch4state, ':fanstate' => $cleaned_fanstate, ':heatertemperature' => $cleaned_heatertemperature, ':keypadvalue' => $cleaned_keypadvalue);

        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $result = $this->database_wrapper->safeQuery($query_string, $query_params);
    }

    /**
     * Executes the Insert Into RetrievedMessages Database query
     */
    public function insertIntoRetrievedMessages($messagesentto, $messagesentfrom, $receivedtime, $bearer, $messageref)
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = 'SELECT stateid FROM circuitboardstates ORDER BY stateid DESC LIMIT 1'; //retrieves the last inserted ID from the CircuitBoardStates table - circuitboardstates query must always be executed before this one

        $last_inserted_id = $this->database_wrapper->safeQuery($query_string);
        $last_inserted_id = $this->database_wrapper->safeFetchRow();
        $last_inserted_id = intval($last_inserted_id[0]);

        $query_string = $this->sql_queries->insertIntoRetrievedMessages();

        $query_params = array(':messagesentto' => $messagesentto, ':messagesentfrom' => $messagesentfrom, 'receivedtime' => $receivedtime,
                                ':bearer' => $bearer, ':messageref' => $messageref, ':stateid' => $last_inserted_id);

        $this->database_wrapper->safeQuery($query_string, $query_params);
    }

    /**
     * Executes the Select From CircuitBoardStates Database query
     */

    public function getMessagesFromDB()
    {
        $this->database_wrapper->setDatabaseConnectionSettings($this->database_connection_settings);
        $this->database_wrapper->makeDatabaseConnection();

        $query_string = $this->sql_queries->getMessagesFromDB();

        $this->database_wrapper->safeQuery($query_string);

        $result = $this->database_wrapper->safeFetchAll();
        return $result;
    }

    /**
     * @param $cleaned_parameters
     *
     * Adds the cleaned detail parameters to the detail variable, which is used in the selectDetail function
     */
    public function setParameters($cleaned_parameters)
    {
        $this->detail = $cleaned_parameters['detail'];
    }

    /**
     * Creates the SOAP Client, and performs the server call using other methods to pass the required information.
     * Passes the returned Soap call message into XML Message to be parsed accordingly.
     */
    public function performDetailRetrieval()
    {
        $soapMessage = null;

        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {
            $webservice_parameters = $this->selectDetail();
            $webservice_function = $webservice_parameters['required_service'];
            $webservice_call_parameters = $webservice_parameters['service_parameters'];

            $soapcall_message = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters);

            $this->xml_message = $soapcall_message;
        }
    }

    /**
     * Executes the SendMessage SOAP function, updating the EE server with the appropriate circuit board state
     */
    public function sendMessage($cleaned_parameters)
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false) {
            $webservice_parameters = $this->selectDetail();
            $webservice_parameters['service_parameters']['message'] = $cleaned_parameters['usermessage'];
            $webservice_function = $webservice_parameters['required_service'];
            $webservice_call_parameters = $webservice_parameters['service_parameters'];

            $soapcall_message = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters);

            $this->xml_message = $soapcall_message;
        }
    }

    /**
     * @return string
     *
     * Retrieves the XML Result from the model - Will be used in the displayMessage route, to show the message on screen
     */
    public function getResult()
    {
        return $this->xml_message;
    }

    /**
     * @return array
     *
     * Selects the required detail from the EE M2M service, providing necessary login information
     */
    private function selectDetail()
    {
        $select_detail = [];
        switch ($this->detail) {
            case 'peekMessages':
                $select_detail['required_service'] = $this->detail;
                $select_detail['service_parameters'] = [
                    'username' => $this->username,
                    'password' => $this->password,
                    'count' => 400,
                    'deviceMsisdn' => $this->msisdn,
                    'countryCode' => '+44',
                ];
                break;
            case 'sendMessage':
                $select_detail['required_service'] = $this->detail;
                $select_detail['service_parameters'] = [
                    'username' => $this->username,
                    'password' => $this->password,
                    'deviceMSISDN' => $this->msisdn,
                    'message' => 'TEMP VALUE',
                    'deliveryReport' => false,
                    'mtBearer' => 'SMS'
                ];
            default:
        }
        return $select_detail;
    }
}
