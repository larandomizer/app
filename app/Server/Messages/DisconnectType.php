<?php

namespace App\Server\Messages;

use App\Server\Commands\CloseConnections;
use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\Connection;
use App\Server\Contracts\SelfHandling;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

abstract class DisconnectType extends Message implements ClientMessage, SelfHandling
{
    use AdminProtection;

    /**
     * Save the message arguments for later when the message is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->type = Connection::ANONYMOUS;
    }

    /**
     * Handle the message.
     */
    public function handle()
    {
        return $this->dispatcher()->run(
            new CloseConnections(array_only($this->attributes, 'type'))
        );
    }
}
