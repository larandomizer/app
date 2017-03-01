<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;
use Carbon\Carbon;

class CurrentUptime extends Message implements ServerMessage
{
    /**
     * Send the current uptime.
     *
     * @param \Carbon\Carbon $start time of server
     */
    public function __construct(Carbon $start)
    {
        $now = Carbon::now();
        $this->elapsed = $now->diffInSeconds($start);
        $this->start = $start->timestamp;
        $this->now = $now->timestamp;
    }
}
