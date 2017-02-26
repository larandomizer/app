<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\AdminProtection;

class StopServer extends Command implements ClientCommand, ServerCommand
{
    use AdminProtection;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->listener()->stop();
        $this->listener()->loop()->stop();
    }
}
