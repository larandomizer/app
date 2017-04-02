<?php

namespace App\Server\Messages;

use App\Server\Commands\NotifyConnection as NotifyConnectionCommand;
use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\SelfHandling;
use App\Server\Entities\Message;
use App\Server\Traits\NoProtection;

class NotifyConnection extends Message implements ClientMessage, SelfHandling
{
    use NoProtection;

    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->receiver = array_get($arguments, 'receiver');
        $this->sender = array_get($arguments, 'sender');
    }

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->dispatcher()->run(
            new NotifyConnectionCommand(array_only($this->attributes, ['receiver', 'sender']))
        );
    }
}
