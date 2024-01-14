<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionCheckPassword extends Action
{
    protected $secure = [
        'session_id'
    ];

    public function handle()
    {
        $email = $this->getData('email');
        $password = $this->getData('password');
        $session_id = $this->getData('session_id');

        if ($password != 'test_password') {
            return;
        }

        if ($email != 'test_email') {
            return;
        }

        if ($session_id != 'some_session_id') {
            return;
        }

        return [
            'result' => true,
            'session_id' => $session_id
        ];
    }
}
