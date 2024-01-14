<?php

namespace VenderaTradingCompany\PHPActions;

class Builder
{
    private mixed $id;

    private mixed $action;

    public function __construct(mixed $action_class = null) {
        $this->action = $action_class;
    }

    public function id(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function action(mixed $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function run(array $data = []): Response
    {
        $action = $this->action;

        $id = $this->id;

        $action_class = (new $action($data, $id));

        return Action::run($action_class);
    }
}
