<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionTrimText extends Action
{
    protected $options = [
        'uppercase' => [
            true,
            false
        ],
        'lowercase' => [
            true,
            false
        ],
        'trim' => [
            'all',
            'left',
            'right',
        ]
    ];

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

        $trimRule = $this->getOption('trim');

        if ($trimRule == 'all') {
            return [
                'result' => trim($text)
            ];
        }

        if ($trimRule == 'left') {
            return [
                'result' => ltrim($text)
            ];
        }

        if ($trimRule == 'right') {
            return [
                'result' => rtrim($text)
            ];
        }

        return [
            'result' => $text
        ];
    }
}
