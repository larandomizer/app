<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;

class CloseConnections extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->uuid = array_get($arguments, 'uuid');
        $this->type = array_get($arguments, 'type');
    }

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run()
    {
        if ($this->uuid) {
            return $this->disconnectUUID($this->uuid);
        }

        if ($this->type) {
            return $this->disconnectByType($this->type);
        }

        return $this->disconnectAll();
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
        return $this->dispatcher()
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
        return $this->dispatcher()
            ->connections()
            ->types($type)
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
        return $this->dispatcher()
            ->connections()
            ->each(function ($connection) {
                $connection->close();
            });
    }
}
