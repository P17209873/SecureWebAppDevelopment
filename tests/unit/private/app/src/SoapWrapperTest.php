<?php


use SecureWebAppCoursework\SoapWrapper;
use PHPUnit\Framework\TestCase;

class SoapWrapperTest extends TestCase
{
    public function testCreateSoapClient() {
        $SoapWrapper = new \SecureWebAppCoursework\SoapWrapper();

        $this->assertNotEquals('Ooops - something went wrong when connecting.  Please try again later', $SoapWrapper -> createSoapClient());
    }

    // Test is dependant on createSoapClient, NOT GOOD
    // TEST ALSO DOESNT ACCOUNT FOR EXCEPTION THROWN AS WOULD NOT BE NULL
    public function testSuccessPerformSoapCall() {
        $SoapWrapper = new \SecureWebAppCoursework\SoapWrapper();

        $soap_client_handle = $SoapWrapper->createSoapClient();

        $webservice_function = 'peekMessages';
        $webservice_call_parameters = [
            'username' => '19p17204157',
            'password' => 'cameraN1nthchair',
            'count' => 50,
        ];
        $this->assertNotEquals(null, $SoapWrapper->performSoapCall($soap_client_handle, $webservice_function, $webservice_call_parameters));
    }
}
