<?php

namespace App\Server;

use Illuminate\Support\Collection;
use Ratchet\ConnectionInterface;

class Connections extends Collection
{
    /**
     * Filter connections to those with the registered type.
     *
     * @param string|array $topics
     *
     * @return self
     */
    public function type($types = null)
    {
        if (is_null($types) || empty($types)) {
            return $this;
        }

        if ( ! is_array($types)) {
            $types = [$types];
        }

        return $this->filter(function ($connection) use ($types) {
            return in_array($connection->type(), $types);
        });
    }

    /**
     * Filter connections to those subscribed to the topics.
     *
     * @param string|array $topics
     *
     * @return self
     */
    public function topic($topics = null)
    {
        if (is_null($topics) || empty($topics)) {
            return $this;
        }

        if ( ! is_array($topics)) {
            $topics = [$topics];
        }

        return $this->filter(function ($connection) use ($topics) {
            return ! empty(array_intersect($connection->topics(), $topics));
        });
    }

    /**
     * Get the first connection that matches the socket connection.
     *
     * @param \Ratchet\ConnectionInterface $socket
     *
     * @return \App\Server\Contracts\Connection|null
     */
    public function socket(ConnectionInterface $socket)
    {
        return $this->first(function ($connection) use ($socket) {
            return $connection->socket() === $socket;
        });
    }

    /**
     * Get the first connection that matches the UUID.
     *
     * @param string $uuid
     *
     * @return \App\Server\Contracts\Connection|null
     */
    public function uuid($uuid)
    {
        return $this->first(function ($connection) use ($uuid) {
            return $connection->uuid() === $uuid;
        });
    }
}
