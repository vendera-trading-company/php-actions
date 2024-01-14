<?php

namespace VenderaTradingCompany\PHPActions\Traits;

trait BelongsToAction
{
    public function uniqueId(): string
    {
        $id = $this->getActionId();

        if (empty($id)) {
            return $this->action_class;
        }

        return $this->action_class . '_' . $id;
    }

    public function handle()
    {
        $id = $this->getActionId();

        $action_class = $this->action_class;

        $action = (new $action_class($this->data, $id));

        return $action->handleAsync();
    }
}
