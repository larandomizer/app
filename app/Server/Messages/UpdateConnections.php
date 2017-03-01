<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Connections;
use App\Server\Entities\Message;

class UpdateConnections extends Message implements ServerMessage
{
    /**
     * Send a list of all the connections.
     *
     * @param \App\Server\Entities\Connections $connections
     */
    public function __construct(Connections $connections)
    {
        $this->connections = $connections->toArray();
    }
}
