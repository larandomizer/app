<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Connection;
use App\Server\Contracts\ServerCommand;

class ConnectionEstablished extends Command implements ServerCommand
{
    /**
     * Send back connection details to the connection that was just established.
     *
     * @param \App\Server\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection->toArray();
    }
}
