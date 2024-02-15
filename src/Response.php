<?php

namespace VenderaTradingCompany\PHPActions;

class Response
{
    private mixed $data = null;
    private string $status;
    private mixed $action;

    public function __construct(mixed $action, mixed $data, string $status)
    {
        $this->action = $action;
        $this->data = $data;
        $this->status = $status;
    }

    public static function done(mixed $action, mixed $data = null): Response
    {
        return new Response($action, $data, 'done');
    }

    public static function error(mixed $action, mixed $data = null): Response
    {
        return new Response($action, $data, 'error');
    }

    public function getData(string | null $key = null): mixed
    {
        if (empty($key)) {
            return $this->data;
        }

        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }

        return null;
    }

    public function isDone(): bool
    {
        return $this->status == 'done';
    }

    public function action(): mixed
    {
        return $this->action;
    }
}