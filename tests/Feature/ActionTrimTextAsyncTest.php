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

        Action::run(ActionTrimTextAsync::class, [
            'text' => $text,
        ]);

        Queue::assertPushed(function (BaseAsyncAction $job) {
            return $job->getActionClass() == ActionTrimTextAsync::class;
        });
    }

    public function testPushesAsyncActionWithCustomId()
    {
        $text = ' Test String ';

        Queue::fake();

        Action::action(ActionTrimTextAsync::class)->id('some_id')->run([
            'text' => $text,
        ]);

        Queue::assertPushed(function (BaseAsyncAction $job) {
            return $job->getActionId() == 'some_id';
        });
    }
}
