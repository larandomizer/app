<?php

namespace App\Server\Entities;

use App\Server\Contracts\Connection;
use App\Server\Traits\UUIDFilter;
use Illuminate\Support\Collection;
use Ratchet\ConnectionInterface;

class Connections extends Collection
{
    use UUIDFilter;

    /**
     * Add a connection to the collection.
     *
     * @param App\Server\Contracts\Connection $connection
     *
     * @return self
     */
    public function add(Connection $connection)
    {
        $this->put($connection->uuid(), $connection);

        return $this;
    }

    /**
     * Remove a connection from the collection.
     *
     * @param App\Server\Contracts\Connection $connection
     *
     * @return self
     */
    public function remove(Connection $connection)
    {
        $this->forget($connection->uuid(), $connection);

        return $this;
    }

    /**
     * Filter connections to those with the registered type.
     *
     * @param string|array $topics
     *
     * @return self
     */
    public function types($types = null)
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
    public function topics($topics = null)
    {
        if (is_null($topics) || empty($topics)) {
            return $this;
        }

        if ( ! is_array($topics)) {
            $topics = [$topics];
        }

        return $this->filter(function ($connection) use ($topics) {
            return ! empty($connection->subscriptions()->intersect($topics));
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
}
