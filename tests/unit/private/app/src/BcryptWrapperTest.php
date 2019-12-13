<?php


use secureWebAppCoursework\BcryptWrapper;
use PHPUnit\Framework\TestCase;

class BcryptWrapperTest extends TestCase
{
    //This fails and im not sure why
    public function testCreateHashedPassword() {
        $bcryptWrapper = new \SecureWebAppCoursework\BcryptWrapper();

        $example_bcrypt_hashed_password = password_hash('fakePassword', PASSWORD_DEFAULT, array('cost' => 12));

        $this->assertEquals($example_bcrypt_hashed_password, $bcryptWrapper -> createHashedPassword('fakePassword'));
    }

    public function testAuthenticatePassword() {
        $bcryptWrapper = new \SecureWebAppCoursework\BcryptWrapper();

        $example_bcrypt_hashed_password = password_hash('fakePassword', PASSWORD_DEFAULT, array('cost' => 12));

        $this->assertTrue($bcryptWrapper -> authenticatePassword('fakePassword', $example_bcrypt_hashed_password));
    }
}
