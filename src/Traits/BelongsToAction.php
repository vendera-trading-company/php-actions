<?php

namespace VenderaTradingCompany\PHPActions\Traits;

trait BelongsToAction
{
    public function uniqueId(): string
    {
        $id = $this->_data['id'] ?? null;

        if (empty($id)) {
            return $this->_data['class'];
        }

        return $this->_data['class'] . '_' . $id;
    }

    public function handle()
    {
        $action_class = $this->_data['class'];

        $action = (new $action_class());

        $action->setId($this->_data['id'] ?? null);
        $action->setData($this->_data['data'] ?? []);
        $action->setOptions($this->_data['options'] ?? []);

        return $action->handleAsync();
    }
}
