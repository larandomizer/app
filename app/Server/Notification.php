<?php

namespace App\Server;

use App\Server\Contracts\Connection;
use App\Server\Contracts\Notification as NotificationInterface;
use App\Server\Traits\DynamicProperties;
use Ramsey\Uuid\Uuid;

class Notification implements NotificationInterface
{
    use DynamicProperties;

    protected $sender;
    protected $uuid;

    /**
     * Instantiate the notification with the sender.
     *
     * @param \App\Server\Contracts\Connection $connection of sender
     */
    public function __construct(Connection $connection)
    {
        $this->uuid(Uuid::uuid4()->toString());
        $this->sender($connection);
    }

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
    public function uuid($uuid = null)
    {
        return $this->dynamic('uuid', $uuid);
    }

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
    public function sender(Connection $connection = null)
    {
        return $this->dynamic('sender', $connection);
    }
}
