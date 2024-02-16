<?php

namespace Tests\Feature;

use Tests\Helpers\ActionMultiplyNumber;
use Tests\TestCase;
use VenderaTradingCompany\PHPActions\Action;

class ActionMultiplyNumberTest extends TestCase
{
    public function testResultIsCorrect()
    {
        $number = 10;
        $multiplier = 234;

        $result = $number * $multiplier;

        $response = Action::build(ActionMultiplyNumber::class)->data([
            'number' => $number,
            'multiplier' => $multiplier
        ])->run();

        $result_number = $response->getData('result');

        $this->assertEquals($result, $result_number);
    }
}
