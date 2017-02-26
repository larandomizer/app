<?php

namespace App\Server\Commands;

use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\Connection;
use App\Server\Contracts\Listener;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\DynamicProperties;
use Illuminate\Support\Fluent;

class StopServer extends Fluent implements ClientCommand, ServerCommand
{
    use DynamicProperties;

    protected $listener;
    protected $client;

    /**
     * Authorize the client connection.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->client()->isAdmin();
    }

    /**
     * Get or set the client connection.
     *
     * @param \App\Server\Contracts\Connection $connection of the client
     *
     * @return \App\Server\Contracts\Connection|self
     */
    public function client(Connection $connection = null)
    {
        return $this->dynamic('client', $connection);
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
        $this->listener()->stop();
        $this->listener()->loop()->stop();
    }
}
