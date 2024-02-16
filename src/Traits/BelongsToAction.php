<?php

namespace VenderaTradingCompany\PHPActions\Traits;

trait BelongsToAction
{
    public function uniqueId(): string
    {
        $id = $this->data()['id'] ?? null;

        if (empty($id)) {
            return $this->data()['class'];
        }

        return $this->data()['class'] . '_' . $id;
    }

    public function handle()
    {
        $action_class = $this->data()['class'];

        $action = (new $action_class());

        $action->setId($this->data()['id'] ?? null);
        $action->setData($this->data()['data'] ?? []);
        $action->setOptions($this->data()['options'] ?? []);

        return $action->handleAsync();
    }
}
