<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;

class GetJob extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $dispatcher = $this->dispatcher();
        $job = $dispatcher->connector()
            ->pop($dispatcher->queue());
        if ( ! $job) {
            return;
        }

        $dispatcher->work($job);
    }
}
