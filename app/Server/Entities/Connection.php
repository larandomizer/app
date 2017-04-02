<?php

namespace App\Server\Entities;

use App\Server\Contracts\Connection as ConnectionInterface;
use App\Server\Contracts\Topic;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\JsonHelpers;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ratchet\ConnectionInterface as SocketInterface;

class Connection implements ConnectionInterface, Arrayable, Jsonable, JsonSerializable
{
    use FluentProperties, JsonHelpers;

    protected $admin;
    protected $attributes = [];
    protected $email;
    protected $ip_address;
    protected $name;
    protected $notifications;
    protected $socket;
    protected $subscriptions;
    protected $timestamp;
    protected $type;
    protected $uuid;

    /**
     * Inject a Ratchet connection as the proxy of this connection.
     *
     * @param \Ratchet\ConnectionInterface $instance
     */
    public function __construct(SocketInterface $instance)
    {
        $this->socket($instance);
        $this->timestamp(Carbon::now());
        $this->uuid(Uuid::uuid4()->toString());
        $this->type(ConnectionInterface::ANONYMOUS);
        $this->notifications(new Notifications());
        $this->subscriptions(new Topics());
        $this->admin(false);

        $header = $instance->WebSocket->request->getHeader('x-forwarded-for');
        $this->ipAddress($header ? $header->__toString() : $instance->remoteAddress);
    }

    /**
     * Get or set the socket for the connection.
     *
     * @example socket() ==> \Ratchet\ConnectionInterface
     *          socket($interface) ==> self
     *
     * @param \Ratchet\ConnectionInterface $interface
     *
     * @return \Ratchet\ConnectionInterface|self
     */
    public function socket(SocketInterface $interface = null)
    {
        return $this->property(__FUNCTION__, $interface);
    }

    /**
     * Get or set the timestamp when the connection was opened.
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
        return $this->property(__FUNCTION__, $uuid);
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
        return $this->property(__FUNCTION__, $type);
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
        return $this->property(__FUNCTION__, $email);
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
        return $this->property(__FUNCTION__, $name);
    }

    /**
     * Get or set the client IP Address for the connection.
     *
     * @example ipAddress() ==> string
     *          ipAddress($name) ==> self
     *
     * @param string $ip_address
     *
     * @return string|self
     */
    public function ipAddress($ip_address = null)
    {
        return $this->property(snake_case(__FUNCTION__), $ip_address);
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
        return $this->property(__FUNCTION__, $privileged);
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
        return $this->property(__FUNCTION__, $topics);
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
        $this->topics()->add($topic);

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
        $this->topics()->remove($topic);

        return $this;
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
        return $this->property(__FUNCTION__, $notifications);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = [];
        foreach ($this->attributes as $key => $value) {
            $attributes[$key] = is_object($value) ? $value->toArray() : $value;
        }

        return array_filter(array_merge($attributes, [
            'admin'         => $this->admin(),
            'email'         => $this->email(),
            'ip_address'    => $this->ipAddress(),
            'name'          => $this->name(),
            'notifications' => $this->notifications()->toArray(),
            'resource_id'   => $this->socket()->resourceId,
            'subscriptions' => $this->subscriptions()->toArray(),
            'timestamp'     => $this->timestamp()->timestamp,
            'type'          => $this->type(),
            'uuid'          => $this->uuid(),
        ]), function ($value) {
            return ((is_array($value) || is_string($value)) && ! empty($value)) || ! is_null($value);
        });
    }

    /**
     * Get or set additional macroable values on the connection.
     *
     * @example foo() ==> mixed
     *          foo($value) ==> self
     *
     * @param string $method
     * @param mixed  $arguments
     *
     * @return mixed|self
     */
    public function __call($method, $arguments = [])
    {
        if (empty($arguments)) {
            return array_get($this->attributes, $method);
        }

        array_set($this->attributes, $method, $arguments);

        return $this;
    }
}
