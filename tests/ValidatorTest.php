
<?php
// tests/ValidatorTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/Service/Validator.php';

use App\Service\Validator;

final class ValidatorTest extends TestCase
{
    public function testChampsRequis()
    {
        $validator = new Validator([
            'email' => '',
            'password' => '',
            'confirm_password' => ''
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'match:password'],
        ]);
        $this->assertFalse($validator->isValid(), 'Doit échouer si champs vides');
        $this->assertArrayHasKey('email', $validator->getErrors());
    }

    public function testEmailInvalide()
    {
        $validator = new Validator([
            'email' => 'foo',
            'password' => 'abcdefgh',
            'confirm_password' => 'abcdefgh'
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'match:password'],
        ]);
        $this->assertFalse($validator->isValid(), 'Email invalide');
        $this->assertArrayHasKey('email', $validator->getErrors());
    }

    public function testPasswordTropCourt()
    {
        $validator = new Validator([
            'email' => 'foo@bar.com',
            'password' => 'abc',
            'confirm_password' => 'abc'
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'match:password'],
        ]);
        $this->assertFalse($validator->isValid(), 'Mot de passe trop court');
        $this->assertArrayHasKey('password', $validator->getErrors());
    }

    public function testPasswordsMismatch()
    {
        $validator = new Validator([
            'email' => 'foo@bar.com',
            'password' => 'abcdefgh',
            'confirm_password' => 'abcdefgi'
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'match:password'],
        ]);
        $this->assertFalse($validator->isValid(), 'Passwords mismatch');
        $this->assertArrayHasKey('confirm_password', $validator->getErrors());
    }

    public function testToutValide()
    {
        $validator = new Validator([
            'email' => 'foo@bar.com',
            'password' => 'abcdefgh',
            'confirm_password' => 'abcdefgh'
        ], [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'match:password'],
        ]);
        $this->assertTrue($validator->isValid(), 'Tout doit être valide');
    }
}
