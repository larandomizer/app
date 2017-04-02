<?php

namespace App\Server\Messages;

use App\Server\Commands\CloseConnections;
use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\SelfHandling;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class DisconnectAll extends Message implements ClientMessage, SelfHandling
{
    use AdminProtection;

    /**
     * Handle the message.
     */
    public function handle()
    {
        return $this->dispatcher()->run(new CloseConnections());
    }
}
