<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;
use App\Server\Messages\CurrentUptime;

class BroadcastCurrentUptime extends Command
{
    protected $start;

    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->start = array_get($arguments, 'start');
    }

    /**
     * Run the command.
     */
    public function run()
    {
        $this->dispatcher()
            ->broadcast(new CurrentUptime($this->start));
    }
}
