<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionLogin extends Action
{
    protected $validator = [
        'email' => 'required',
        'password' => 'required'
    ];

    public function handle()
    {
        $response = Action::run(ActionCheckPassword::class, [
            'session_id' => 'some_session_id'
        ]);

        $matches = $response->getData('result');

        if (empty($matches)) {
            return;
        }

        $session_id = $response->getData('session_id');

        return [
            'result' => true,
            'session_id' => $session_id
        ];
    }
}
