<?php


class AuthControllerTest extends TestCase
{
    public function testSignInWithCorrectEmailAndPasswordShouldReturnTTLAndToken()
    {
        $this->json('POST', '/auth/sign_in', [
            'email' => '658655@gmail.com',
            'password' => '123456'
        ])
            ->seeJsonStructure([
                'ttl',
                'token'
            ]);
    }
}
