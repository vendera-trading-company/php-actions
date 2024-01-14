<?php

namespace Tests\Feature;

use Tests\Helpers\ActionTrimText;
use Tests\TestCase;
use VenderaTradingCompany\PHPActions\Action;

class ActionTrimTextTest extends TestCase
{
    public function testResultIsCorrect()
    {
        $text = ' Test String ';
        $trimmed_text = trim($text);

        $response = Action::run(ActionTrimText::class, [
            'text' => $text,
        ]);

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectOnEmptyInput()
    {
        $text = null;

        $response = Action::run(ActionTrimText::class, [
            'text' => $text,
        ]);

        $result_text = $response->getData('result');

        $this->assertEquals(null, $result_text);
    }
}
