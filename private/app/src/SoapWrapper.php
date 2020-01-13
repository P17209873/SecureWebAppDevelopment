<?php

namespace SecureWebAppCoursework;

/**
 * SoapWrapper.php
 *
 * Contains all of the necessary functions to create the SOAP client and perform the SOAP call.
 */
class SoapWrapper
{

    public function __construct()
    {
    }
    public function __destruct()
    {
    }

    /**
     * Creates the SOAP client
     */
    public function createSoapClient()
    {
        $soap_client_handle = false;
        $soap_client_parameters = array();
        $exception = '';
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
            //var_dump($soap_client_handle->__getFunctions());
        } catch (\SoapFault $exception) {
            $soap_client_handle = 'Ooops - something went wrong when connecting.  Please try again later';
        }
        return $soap_client_handle;
    }
  
     /**
     * Performs the SOAP call
     */
    public function performSoapCall($soap_client, $webservice_function, $webservice_call_parameters)
    {
        $soap_call_result = null;
        $raw_xml = '';

        if ($soap_client) {
            try {
                if ($webservice_function == 'peekMessages') {
                    $soap_call_result = $soap_client->{$webservice_function}($webservice_call_parameters['username'], $webservice_call_parameters['password'], $webservice_call_parameters['count']);
                } elseif ($webservice_function == 'sendMessage') {
                    $soap_call_result = $soap_client->{$webservice_function}(
                        $webservice_call_parameters['username'],
                        $webservice_call_parameters['password'],
                        $webservice_call_parameters['deviceMSISDN'],
                        $webservice_call_parameters['message'],
                        $webservice_call_parameters['deliveryReport'],
                        $webservice_call_parameters['mtBearer']
                    );
                }
            } catch (\SoapFault $exception) {
                $soap_call_result = $exception;
            }
        }
        return $soap_call_result;
    }
}
