<?php

namespace App\Server\Entities;

use App\Server\Contracts\Connection as ConnectionInterface;
use App\Server\Contracts\Prize;
use App\Server\Contracts\Topic;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\JsonHelpers;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ratchet\ConnectionInterface as SocketInterface;

class Connection implements ConnectionInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

    protected $admin;
    protected $email;
    protected $name;
    protected $notifications;
    protected $prize;
    protected $socket;
    protected $subscriptions;
    protected $type;
    protected $uuid;

    /**
     * Inject a Ratchet connection as the proxy of this connection.
     */
    public function __construct(SocketInterface $instance)
    {
        $this->socket($instance);
        $this->uuid(Uuid::uuid4()->toString());
        $this->type(ConnectionInterface::ANONYMOUS);
        $this->notifications(new Notifications());
        $this->subscriptions(new Topics());
        $this->admin(false);
    }

    /**
     * Get or set the socket for the connection.
     *
     * @example socket() ==> \Ratchet\ConnectionInterface
     *          socket($interface) ==> self
     *
     * @param \Ratchet\ConnectionInterface $instance
     *
     * @return \Ratchet\ConnectionInterface|self
     */
    public function socket(SocketInterface $interface = null)
    {
        return $this->property(__METHOD__, $interface);
    }

    /**
     * Send data to the connection.
     *
     * @param string $data
     *
     * @return \App\Server\Contracts\Connection
     */
    public function send($data)
    {
        $this->socket()->send($data);
    }

    /**
     * Close the connection.
     */
    public function close()
    {
        $this->socket()->close();
    }

    /**
     * Get or set the UUID of the connection.
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
        return $this->property(__METHOD__, $uuid);
    }

    /**
     * Get or set the type of connection.
     *
     * @example type() ==> string
     *          type($type) ==> self
     *
     * @param string $type
     *
     * @return string|self
     */
    public function type($type = null)
    {
        return $this->property(__METHOD__, $type);
    }

    /**
     * Get or set the email registered for the connection.
     *
     * @example email() ==> string
     *          email($email) ==> self
     *
     * @param string $email
     *
     * @return string|self
     */
    public function email($email = null)
    {
        return $this->property(__METHOD__, $email);
    }

    /**
     * Get or set the name registered for the connection.
     *
     * @example name() ==> string
     *          name($name) ==> self
     *
     * @param string $name
     *
     * @return string|self
     */
    public function name($name = null)
    {
        return $this->property(__METHOD__, $name);
    }

    /**
     * Get or set that the connection is admin privileged.
     *
     * @example admin() ==> true
     *          admin(true) ==> self
     *
     * @param bool $privileged
     *
     * @return bool|self
     */
    public function admin($privileged = null)
    {
        return $this->property(__METHOD__, $privileged);
    }

    /**
     * Get or set the topics the connection subscribes to.
     *
     * @example subscriptions() ==> \App\Server\Entities\Topics
     *          subscriptions($topics) ==> self
     *
     * @param \App\Server\Entities\Topics $topics
     *
     * @return \App\Server\Entities\Topics|self
     */
    public function subscriptions(Topics $topics = null)
    {
        return $this->property(__METHOD__, $topics);
    }

    /**
     * Add a topic that the connection subscribes to.
     *
     * @param \App\Server\Contracts\Topic $topic to subscribe to.
     *
     * @return self
     */
    public function subscribe(Topic $topic)
    {
        $this->topics()->put($topic->uuid(), $topic);

        return $this;
    }

    /**
     * Remove a topic that the connection is subscribed to.
     *
     * @param \App\Server\Contracts\Topic $topic to unsubscribe from.
     *
     * @return self
     */
    public function unsubscribe(Topic $topic)
    {
        $this->topics()->forget($topic->uuid());

        return $this;
    }

    /**
     * Get or set the prize the connection won.
     *
     * @example prize() ==> \App\Server\Contracts\Prize
     *          prize($prize) ==> self
     *
     * @param \App\Server\Contracts\Prize $prize
     *
     * @return \App\Server\Contracts\Prize|self
     */
    public function prize(Prize $prize = null)
    {
        return $this->property(__METHOD__, $prize);
    }

    /**
     * Get or set the notifications collection for the connection.
     *
     * @example notifications() ==> \App\Server\Entities\Notifications
     *          notifications($notifications) ==> self
     *
     * @param \App\Server\Entities\Notifications $notifications
     *
     * @return \App\Server\Entities\Notifications|self
     */
    public function notifications(Notifications $notifications = null)
    {
        return $this->property(__METHOD__, $notifications);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'uuid'          => $this->uuid,
            'resource_id'   => $this->socket->resourceId,
            'type'          => $this->type,
            'admin'         => $this->admin,
            'name'          => $this->name,
            'notifications' => $this->notifications->toArray(),
            'subscriptions' => $this->subscriptions->toArray(),
            'prize'         => $this->prize ? $this->prize->toArray() : null,
        ]);
    }
}
