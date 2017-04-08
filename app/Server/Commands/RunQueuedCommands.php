<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;

class RunQueuedCommands extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $dispatcher = $this->dispatcher();
        $commands = $dispatcher->commands();

        $commands->each(function ($command) use ($dispatcher, $commands) {
            $dispatcher->run($command);
            $commands->remove($command);
        });
    }
}
