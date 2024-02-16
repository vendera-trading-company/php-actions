<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Queue;
use Tests\Helpers\ActionTrimTextAsync;
use Tests\TestCase;
use VenderaTradingCompany\PHPActions\Action;
use VenderaTradingCompany\PHPActions\Jobs\BaseAsyncAction;

class ActionTrimTextAsyncTest extends TestCase
{
    public function testPushesAsyncAction()
    {
        $text = ' Test String ';

        Queue::fake();

        Action::build(ActionTrimTextAsync::class)->data([
            'text' => $text,
        ])->run();

        Queue::assertPushed(function (BaseAsyncAction $job) {
            return $job->data()['class'] == ActionTrimTextAsync::class;
        });
    }

    public function testPushesAsyncActionWithCustomId()
    {
        $text = ' Test String ';

        Queue::fake();

        $id = 'some_id';

        $expected_id = ActionTrimTextAsync::class . '_' . $id;

        Action::build(ActionTrimTextAsync::class)->id($id)->data([
            'text' => $text,
        ])->run();

        Queue::assertPushed(function (BaseAsyncAction $job) use ($expected_id) {
            $this->assertEquals($expected_id, $job->uniqueId());

            return $job->uniqueId() == $expected_id;
        });
    }
}
