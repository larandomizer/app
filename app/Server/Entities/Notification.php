<?php

namespace App\Server\Entities;

use App\Server\Contracts\Notification as NotificationInterface;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\JsonHelpers;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

class Notification implements NotificationInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

    protected $sender;
    protected $timestamp;
    protected $uuid;

    /**
     * Instantiate the notification with the sender.
     *
     * @param string $sender UUID
     */
    public function __construct($sender)
    {
        $this->uuid(Uuid::uuid4()->toString());
        $this->sender($sender);
        $this->timestamp(Carbon::now());
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
     * @example sender() ==> string
     *          sender($uuid) ==> self
     *
     * @param string $uuid
     *
     * @return string|self
     */
    public function sender($uuid = null)
    {
        return $this->property(__FUNCTION__, $uuid);
    }

    /**
     * Get or set the time that the notification was sent.
     *
     * @example timestamp() ==> \Carbon\Carbon
     *          timestamp($timestamp) ==> self
     *
     * @param \Carbon\Carbon $timestamp
     *
     * @return \Carbon\Carbon|self
     */
    public function timestamp(Carbon $timestamp = null)
    {
        return $this->property(__FUNCTION__, $timestamp);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'uuid'      => $this->uuid(),
            'sender'    => $this->sender(),
            'timestamp' => $this->timestamp()->timestamp,
        ]);
    }
}
