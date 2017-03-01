<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;

class AddQueueWorker extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->timing = array_get($arguments, 'timing', 1 / 100);
    }

    /**
     * Run the command.
     */
    public function run()
    {
        $this->dispatcher()->loop()->addPeriodicTimer($this->timing, function () {

            // Get a job from the queue
            $job = $this->dispatcher()->connector()->pop($this->dispatcher()->queue());
            if ( ! $job) {
                return;
            }
            $this->dispatcher()->work($job);
        });
    }
}
