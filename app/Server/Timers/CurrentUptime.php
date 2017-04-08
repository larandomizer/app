<?php

namespace App\Server\Timers;

use App\Server\Commands\BroadcastCurrentUptime;
use App\Server\Contracts\ShouldAutoStart;
use App\Server\Entities\Timer;
use Carbon\Carbon;

class CurrentUptime extends Timer implements ShouldAutoStart
{
    /**
     * Setup timer command.
     *
     * @param \Carbon\Carbon $start of server
     *
     * @return self
     */
    public function __construct(Carbon $start)
    {
        $this->command(new BroadcastCurrentUptime(compact('start')))
            ->interval(1000);
    }
}
