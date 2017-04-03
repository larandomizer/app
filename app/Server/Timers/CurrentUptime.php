<?php

namespace App\Server\Timers;

use App\Server\Contracts\ShouldAutoStart;
use App\Server\Entities\Timer;
use App\Server\Messages\CurrentUptime as CurrentUptimeMessage;
use Carbon\Carbon;

class CurrentUptime extends Timer implements ShouldAutoStart
{
    protected $interval = 1;
    protected $start;

    /**
     * Setup timer command.
     *
     * @param \Carbon\Carbon $start of server
     *
     * @return self
     */
    public function __construct(Carbon $start)
    {
        $this->start = $start;
    }

    /**
     * Run the command when the timer interval calls for it.
     *
     * @return mixed
     */
    public function run()
    {
        return $this->dispatcher()
            ->broadcast(new CurrentUptimeMessage($this->start));
    }
}
