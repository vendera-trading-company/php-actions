<?php

namespace VenderaTradingCompany\PHPActions\Jobs;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

abstract class AsyncAction implements ShouldBeUnique, ShouldQueue
{
    protected array | null $data = [];
    protected string $action_class = '';
    protected string | null $action_id = '';

    public function __construct(string $action_class, array | null $data = [], string | null $action_id = null)
    {
        $this->data = $data;
        $this->action_class = $action_class;
        $this->action_id = $action_id;
    }

    public function getActionId(): string | null
    {
        return $this->action_id;
    }

    public function getData(string $key): mixed
    {
        return $this->data[$key] ?? null;
    }

    public function getActionClass(): string | null
    {
        return $this->action_class;
    }
}
