<?php

namespace SecureWebAppCoursework;

/**
 * SecureWebAppModel.php
 *
 * This class acts as the Data Model of the application, containing all methods that implement and manage the data logic
 * required to access the M2M SOAP Service
 */
class SecureWebAppModel
{
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

        if ($soap_client_handle !== false)
        {
            $webservice_parameters = $this->selectDetail();
            $webservice_function = $webservice_parameters['required_service'];
            $webservice_call_parameters = $webservice_parameters['service_parameters'];

            $soapcall_message = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters);

            $this->xml_message = $soapcall_message;
        }
    }

    /**
     * Send message
     */
    public function sendMessage($cleaned_parameters)
    {
        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false)
        {
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
        switch($this->detail)
        {
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
