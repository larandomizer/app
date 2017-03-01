<?php

namespace App\Server\Contracts;

interface Notification
{
    /**
     * Get or set the UUID of the notification.
     *
     * @example uuid() ==> string
     *          uuid($uuid) ==> self
     *
     * @param string $uuid
     *
     * @return string|self
     */
    public function uuid($uuid = null);

    /**
     * Get or set the connection that sent the notification.
     *
     * @example connection() ==> \App\Server\Contracts\Connection
     *          connection($connection) ==> self
     *
     * @param \App\Server\Contracts\Connection $connection
     *
     * @return \App\Server\Contracts\Connection|self
     */
    public function sender(Connection $connection = null);
}
