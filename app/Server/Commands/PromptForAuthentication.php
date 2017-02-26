<?php

namespace App\Server\Commands;

use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\Listener;
use App\Server\Contracts\ServerCommand;
use Illuminate\Support\Fluent;

class PromptForAuthentication extends Fluent implements ServerCommand
{
    /**
     * Prompt for authentication before authorizing the previous client command.
     *
     * @param \App\Server\Contracts\ClientCommand $command
     */
    public function __construct(ClientCommand $command)
    {
        $this->previous_command = $command;
        $this->message          = 'Authorization required.';
        $this->code             = 401;
    }

    /**
     * Get or set the connection listener.
     *
     * @param \App\Server\Contracts\Listener $interface for the server
     *
     * @return \App\Server\Contracts\Listener|self
     */
    public function listener(Listener $interface = null)
    {
        return $this->dynamic('listener', $interface);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
    }
}
