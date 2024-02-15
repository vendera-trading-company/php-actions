<?php

namespace VenderaTradingCompany\PHPActions;

class Run
{
    public static function action(mixed $action): Response
    {
        $response = null;

        if ($action->validate()) {
            $response = $action->handle();
        } else {
            $response = $action->error();
        }

        $response = Action::parse_action_handle_response($action, $response);

        if (!$response->isDone()) {
            $errorResponse = $action->error();

            $errorResponse = Action::parse_action_error_response($action, $errorResponse);

            if (!empty($errorResponse)) {
                $response = $errorResponse;
            }
        }

        /** Actions can also be executed asynchronously in conjunction with Laravel. */
        if ($response->isDone() && method_exists($action, 'dispatchJob')) {
            $action->dispatchJob($response->getData());

            return Response::done($action, $response);
        }

        return $response;
    }
}
