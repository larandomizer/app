<?php

namespace App\Server\Messages;

use App\Server\Contracts\Connection;
use App\Server\Messages\JoinAsType;

class JoinAsPlayer extends JoinAsType
{
    /**
     * Save the message arguments for later when the message is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->type = Connection::PLAYER;
    }
}
