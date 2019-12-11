<?php


use SecureWebAppCoursework\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testTrueValidateDetailType() {
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals('peekMessages', $validator -> validateDetailType('peekMessages'));
    }
    public function testFalseValidateDetailType() {
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals(false, $validator -> validateDetailType('randomString'));
    }

    public function testTrueValidateDownloadedData() {
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals('randomString', $validator -> validateDownloadedData('randomString'));
    }
    public function testFalseValidateDownloadedData() { //HOW DO I TEST FALSE
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals('randomString', $validator -> validateDownloadedData('randomString'));
    }

    public function testTrueSanitiseString() {
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals('randomString', $validator -> sanitiseString('randomString'));
    }
    public function testFalseSanitiseString() { //HOW DO I TEST FALSE
        $validator = new \SecureWebAppCoursework\Validator();

        $this->assertEquals('randomString', $validator -> sanitiseString('randomString'));
    }
}
