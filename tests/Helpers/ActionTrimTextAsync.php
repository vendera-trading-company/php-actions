<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\PHPActions\Traits\RunsAsync;

class ActionTrimTextAsync extends Action
{
    use RunsAsync;

    public function handle()
    {
        $text = $this->getData('text');
        $action_id = $this->getData('action_id');

        if (empty($text)) {
            return;
        }

        return [
            'text' => $text,
            'action_id' => $action_id
        ];
    }

    public function handleAsync()
    {
        $text = $this->getData('text');

        if (empty($text)) {
            return $text;
        }

        return [
            'result' => trim($text)
        ];
    }
}
