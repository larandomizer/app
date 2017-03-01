<?php

namespace App\Server\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;

class PromptForAuthentication extends Message implements ServerMessage
{
    /**
     * Prompt for authentication before authorizing the previous client message.
     *
     * @param \App\Server\Contracts\ClientMessage $message
     */
    public function __construct(ClientMessage $message)
    {
        $this->previous = $message->toArray();
        $this->message  = 'Authorization required.';
        $this->code     = 401;
    }
}
