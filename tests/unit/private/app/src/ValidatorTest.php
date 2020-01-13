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
}
