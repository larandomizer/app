<?php

namespace App\Server;

use App\Server\Contracts\Connection as ConnectionInterface;
use App\Server\Contracts\Prize;
use App\Server\Traits\DynamicProperties;
use Ramsey\Uuid\Uuid;
use Ratchet\ConnectionInterface as SocketInterface;

class Connection implements ConnectionInterface
{
    use DynamicProperties;

    protected $email;
    protected $socket;
    protected $type;
    protected $name;
    protected $notifications;
    protected $prize;
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
        return $this->dynamic('socket', $interface);
    }

    /**
     * Get or set the notifications collection for the connection.
     *
     * @example notifications() ==> \App\Server\Notifications
     *          notifications($collection) ==> self
     *
     * @param \App\Server\Notifications $collection
     *
     * @return \App\Server\Notifications|self
     */
    public function notifications(Notifications $collection = null)
    {
        return $this->dynamic('notifications', $collection);
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
        return $this->dynamic('uuid', $uuid);
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
        return $this->dynamic('type', $type);
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
        return $this->dynamic('email', $email);
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
        return $this->dynamic('name', $name);
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
        return $this->dynamic('prize', $prize);
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
     * Check if the connection is an admin connection.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return false; // @todo should check if connection password matches server
    }
}
