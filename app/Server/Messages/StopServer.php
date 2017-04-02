<?php

namespace App\Server\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\SelfHandling;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class StopServer extends Message implements ClientMessage, SelfHandling
{
    use AdminProtection;

    /**
     * Handle the message.
     */
    public function handle()
    {
        return $this->dispatcher()->stop();
    }
}
