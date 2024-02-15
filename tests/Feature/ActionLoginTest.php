<?php

namespace Tests\Feature;

use Tests\TestCase;

class ActionLoginTest extends TestCase
{
    public function testCanLogin()
    {
        $email = 'test_email';
        $password = 'test_password';

        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password
        ]);

        $logged_in = $response->json('logged_in');

        $this->assertTrue($logged_in);
    }

    public function testSecuredFieldSessionIdCannotChangedByRequest()
    {
        $email = 'test_email';
        $password = 'test_password';
        $session_id = 'other_session_id';

        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password,
            'session_id' => $session_id
        ]);

        $logged_in = $response->json('logged_in');

        $this->assertTrue($logged_in);

        $this->assertNotEquals('some_session_id', $session_id);
        $this->assertEquals('some_session_id', $response->json('session_id'));
    }

    public function testCanNotLoginWithWrongEmail()
    {
        $email = 'wrong_email';
        $password = 'test_password';

        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password
        ]);

        $logged_in = $response->json('logged_in');

        $this->assertFalse($logged_in);
    }

    public function testCanNotLoginWithWrongPassword()
    {
        $email = 'email_email';
        $password = 'wrong_password';

        $response = $this->post(route('login'), [
            'email' => $email, 
            'password' => $password
        ]);

        $logged_in = $response->json('logged_in');

        $this->assertFalse($logged_in);
    }
}
