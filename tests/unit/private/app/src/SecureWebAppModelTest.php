<?php


use SecureWebAppCoursework\SecureWebAppModel;
use PHPUnit\Framework\TestCase;

class SecureWebAppModelTest extends TestCase
{
    public function testTrueSelectDetail() {
        $secureWebAppModel = new \SecureWebAppCoursework\SecureWebAppModel();
        $SoapWrapper = new \SecureWebAppCoursework\SoapWrapper();

        $secureWebAppModel -> setSoapWrapper($SoapWrapper);

        $cleaned_parameters['detail'] = 'peekMessages';
        $secureWebAppModel -> setParameters($cleaned_parameters);
        $secureWebAppModel -> performDetailRetrieval();

        $this->assertNotEquals([], $secureWebAppModel -> getResult());
    }
}
