<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;

class PromptForAuthentication extends Command implements ServerCommand
{
    /**
     * Prompt for authentication before authorizing the previous client command.
     *
     * @param \App\Server\Contracts\ClientCommand $command
     */
    public function __construct(ClientCommand $command)
    {
        $this->previous = $command;
        $this->message  = 'Authorization required.';
        $this->code     = 401;
    }
}
