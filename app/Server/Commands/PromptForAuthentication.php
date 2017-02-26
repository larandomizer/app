<?php

namespace App\Server\Commands;

use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use Illuminate\Support\Fluent;

class PromptForAuthentication extends Fluent implements ServerCommand
{
    /**
     * Prompt for authentication before authorizing the previous client command.
     *
     * @param \App\Server\Contracts\ClientCommand $command
     */
    public function __constructor(ClientCommand $command)
    {
        $this->previous_command = $command;
        $this->message          = 'Authorization required.';
        $this->code             = 401;
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
    }
}
