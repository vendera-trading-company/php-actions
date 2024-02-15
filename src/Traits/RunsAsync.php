<?php

namespace VenderaTradingCompany\PHPActions\Traits;

use VenderaTradingCompany\PHPActions\Jobs\BaseAsyncAction;

/** Use the handleAsync() method to execute your task asynchronously */
trait RunsAsync
{
    /** If an empty value or a boolean false value is returned, the action is interrupted. */
    public function handle()
    {
        return true;
    }

    public function dispatchJob(array | null $data = [])
    {
        return BaseAsyncAction::dispatch([
            'class' => $this::class,
            'data' => $data,
            'id' => $this->_id,
            'options' => $this->options
        ]);
    }
}
