<?php

namespace Tests\Helpers;

use VenderaTradingCompany\PHPActions\Action;

class ActionMultiplyNumber extends Action
{
    public function handle()
    {
        $number = $this->getData('number');
        $multiplier = $this->getData('multiplier');

        return [
            'result' => $number * $multiplier
        ];
    }
}
