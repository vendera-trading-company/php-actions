<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionTrimText extends Action
{
    public function handle()
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
