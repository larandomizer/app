<?php

namespace App\Server\Messages;

use App\Server\Contracts\Connection;
use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;

class ConnectionAuthenticated extends Message implements ServerMessage
{
    /**
     * Send back connection details to the connection that was just established.
     *
     * @param \App\Server\Contracts\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection->toArray();
    }
}
