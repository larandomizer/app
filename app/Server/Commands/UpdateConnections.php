<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Connections;
use App\Server\Contracts\ServerCommand;

class UpdateConnections extends Command implements ServerCommand
{
    /**
     * Send a list of all the connections.
     *
     * @param \App\Server\Connections $connections
     */
    public function __construct(Connections $connections)
    {
        $this->connections = $connections->toArray();
    }
}
