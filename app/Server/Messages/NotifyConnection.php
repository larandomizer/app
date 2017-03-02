<?php

namespace App\Server\Messages;

use App\Server\Commands\NotifyConnection as NotifyConnectionCommand;
use App\Server\Entities\Message;

class NotifyConnection extends Message
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->receiver = array_get($arguments, 'receiver');
        $this->sender = array_get($arguments, 'sender');
    }

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run()
    {
        return $this->dispatcher()->run(new NotifyConnectionCommand($this->attributes));
    }
}
