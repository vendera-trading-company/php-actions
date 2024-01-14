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

        $response = Action::run(ActionMultiplyNumber::class, [
            'number' => $number,
            'multiplier' => $multiplier
        ]);

        $result_number = $response->getData('result');

        $this->assertEquals($result, $result_number);
    }
}
