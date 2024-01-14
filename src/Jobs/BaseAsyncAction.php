<?php

namespace VenderaTradingCompany\PHPActions\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use VenderaTradingCompany\PHPActions\Traits\BelongsToAction;

class BaseAsyncAction extends AsyncAction
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BelongsToAction;
}
