<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;

class StopServer extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $this->dispatcher()->stop();
    }
}
