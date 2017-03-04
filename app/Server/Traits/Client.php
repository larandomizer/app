<?php

namespace App\Server\Traits;

use App\Server\Contracts\Connection;

trait Client
{
    protected $client;

    /**
     * Get or set the client connection.
     *
     * @param \App\Server\Contracts\Connection $connection of the client
     *
     * @return \App\Server\Contracts\Connection|self
     */
    public function client(Connection $connection = null)
    {
        return $this->property(__FUNCTION__, $connection);
    }
}
