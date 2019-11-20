<?php


namespace SecureWebAppCoursework;


class SecureWebAppModel
{
    private $xml_message;
    private $xml_parser;
    private $soap_wrapper;
    //private $MSISDN;
    private $detail;
    private $username;
    private $password;

    public function __construct()
    {
        $this->soap_wrapper = null;
        $this->xml_parser = null;
        $this->xml_message = '';
        $this->username = '19p17204157';
        $this->password = 'cameraN1nthchair';
    }

    public function __destruct(){}

    public function setSoapWrapper($soap_wrapper)
    {
        $this->soap_wrapper = $soap_wrapper;
    }

    public function setParameters($cleaned_parameters)
    {
        $this->detail = $cleaned_parameters['detail'];
    }

    public function performDetailRetrieval()
    {
        $soapMessage = null;

        $soap_client_handle = $this->soap_wrapper->createSoapClient();

        if ($soap_client_handle !== false)
        {
            $webservice_parameters = $this->selectDetail();
            $webservice_function = $webservice_parameters['required_service'];
            $webservice_call_parameters = $webservice_parameters['service_parameters'];
            $webservice_object_name = $webservice_parameters['result_message'];

            $soapcall_message = $this->soap_wrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters, $webservice_object_name);

            $this->xml_message = $soapcall_message;
        }
    }

    public function getResult()
    {
        return $this->xml_message;
    }

    private function selectDetail()
    {
        $select_detail = [];
        //$this->MSISDN = '+447817814149';
        switch($this->detail)
        {
            case 'peekMessages':
                $select_detail['required_service'] = 'peekMessages';
                $select_detail['service_parameters'] = [
                    'username' => $this->username,
                    'password' => $this->password,
                    'count' => 100,
                    //'deviceMSISDN' => $this->MSISDN,
                    //'countryCode' => '+44',
                ];
                $select_detail['result_message'] = 'peekMessagesResult';
                break;
            default:
        }
        return $select_detail;
    }
}