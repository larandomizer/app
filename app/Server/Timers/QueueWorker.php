<?php

namespace App\Server\Timers;

use App\Server\Commands\GetJob;
use App\Server\Contracts\ShouldAutoStart;
use App\Server\Entities\Timer;

class QueueWorker extends Timer implements ShouldAutoStart
{
    /**
     * Setup the timed command.
     */
    public function __construct()
    {
        $this->command(GetJob::class)
            ->interval(1 / 10);
    }
}
