<?php

namespace App\Server\Timers;

use App\Server\Commands\StopServer;
use App\Server\Contracts\ShouldAutoStart;
use App\Server\Entities\Timer;

class AutoRestartServer extends Timer implements ShouldAutoStart
{
    /**
     * Setup the timed command.
     */
    public function __construct()
    {
        $this->command(StopServer::class)
            ->interval(3600);
    }
}
