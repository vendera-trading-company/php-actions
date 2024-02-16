<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionTrimText extends Action
{
    public function handle()
    {
        $text = $this->getData('text');

        $uppercase = $this->matchOption('uppercase', true);
        $lowercase = $this->matchOption('lowercase', true);

        if (empty($text)) {
            return $text;
        }

        if ($uppercase) {
            return [
                'result' => strtoupper(trim($text))
            ];
        }

        if ($lowercase) {
            return [
                'result' => strtolower(trim($text))
            ];
        }

        return [
            'result' => trim($text)
        ];
    }
}
