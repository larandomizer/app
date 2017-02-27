<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;

class CloseConnections extends Command implements ClientCommand, ServerCommand
{
    use ClientProtection;

    /**
     * Save the command arguments for later when the command is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->uuid = array_get($arguments, 'uuid');
        $this->type = array_get($arguments, 'type');
    }

    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->uuid && $this->canDisconnectUUID()) {
            return $this->disconnectUUID($this->uuid);
        }

        if ($this->type) {
            return $this->disconnectByType($this->type);
        }

        return $this->disconnectAll();
    }

    /**
     * Check if the command caller can disconnect a UUID.
     *
     * @return bool
     */
    protected function canDisconnectUUID()
    {
        return ! $this->client() || $this->authorize();
    }

    /**
     * Disconnect the connection having the UUID.
     *
     * @param string $uuid of connection
     *
     * @return mixed
     */
    protected function disconnectUUID($uuid)
    {
        return $this->listener()
            ->connections()
            ->uuid($uuid)
            ->close();
    }

    /**
     * Disconnect the connections with the type.
     *
     * @param string $type of connection
     *
     * @return mixed
     */
    protected function disconnectByType($type)
    {
        return $this->listener()
            ->connections()
            ->type($type)
            ->each(function ($connection) {
                $connection->close();
            });
    }

    /**
     * Disconnect all the connections.
     *
     * @return mixed
     */
    protected function disconnectAll()
    {
        return $this->listener()
            ->connections()
            ->each(function ($connection) {
                $connection->close();
            });
    }
}
