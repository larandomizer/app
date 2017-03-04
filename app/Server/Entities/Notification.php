<?php

namespace App\Server\Entities;

use App\Server\Contracts\Connection;
use App\Server\Contracts\Notification as NotificationInterface;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\JsonHelpers;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Notification implements NotificationInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

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
        return $this->property(__FUNCTION__, $uuid);
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
        return $this->property(__FUNCTION__, $connection);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'uuid'   => $this->uuid,
            'sender' => $this->sender->toArray(),
        ]);
    }
}
