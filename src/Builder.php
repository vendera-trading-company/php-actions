<?php

namespace VenderaTradingCompany\PHPActions;

class Builder
{
    private mixed $_id = null;

    private mixed $action_class;

    private mixed $_options = [];

    private mixed $_data = [];

    public function __construct(mixed $action_class = null)
    {
        $this->action_class = $action_class;
    }

    public function id(string $id): self
    {
        $this->_id = $id;

        return $this;
    }

    public function options(array $options): self
    {
        $this->_options = $options;

        return $this;
    }

    public function data(array | null $data): self
    {
        $this->_data = $data;

        return $this;
    }

    public function run(): Response
    {
        $action_class = $this->action_class;

        $action = (new $action_class());

        $action->setData($this->_data);
        $action->setId($this->_id);
        $action->setOptions($this->_options);

        return Run::action($action);
    }
}
