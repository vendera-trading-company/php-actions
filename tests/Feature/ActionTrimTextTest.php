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

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithOptionUppercase()
    {
        $text = ' Test String ';
        $trimmed_text = strtoupper(trim($text));

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'uppercase' => true
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithOptionLowercase()
    {
        $text = ' Test String ';
        $trimmed_text = strtolower(trim($text));

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'lowercase' => true
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithOptionLeft()
    {
        $text = ' Test String ';
        $trimmed_text = ltrim($text);

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'trim' => 'left'
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithOptionRight()
    {
        $text = ' Test String ';
        $trimmed_text = rtrim($text);

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'trim' => 'right'
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithOptionAll()
    {
        $text = ' Test String ';
        $trimmed_text = trim($text);

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'trim' => 'all'
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectWithWrongOption()
    {
        $text = ' Test String ';
        $trimmed_text = trim($text);

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->options([
            'trim' => 'wrong_option'
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals($trimmed_text, $result_text);
    }

    public function testResultIsCorrectOnEmptyInput()
    {
        $text = null;

        $response = Action::build(ActionTrimText::class)->data([
            'text' => $text,
        ])->run();

        $result_text = $response->getData('result');

        $this->assertEquals(null, $result_text);
    }
}
