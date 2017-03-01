<?php

namespace App\Server\Contracts;

interface ClientMessage extends Message
{
    /**
     * Authorize the client connection.
     *
     * @return bool
     */
    public function authorize();

    /**
     * Get or set the client connection.
     *
     * @param \App\Server\Contracts\Connection $connection of the client
     *
     * @return \App\Server\Contracts\Connection|self
     */
    public function client(Connection $connection = null);
}
