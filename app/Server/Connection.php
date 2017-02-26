<?php

namespace App\Server;

use App\Server\Contracts\Connection as ConnectionInterface;
use App\Server\Traits\DynamicProperties;
use Ramsey\Uuid\Uuid;
use Ratchet\ConnectionInterface as SocketInterface;

class Connection implements ConnectionInterface
{
    use DynamicProperties;

    /**
     * Inject a Ratchet connection as the proxy of this connection.
     */
    public function __constructor(SocketInterface $instance)
    {
        $this->socket($instance);
        $this->uuid(Uuid::uuid4());
        $this->type(ConnectionInterface::ANONYMOUS);
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
     * Get or set the username registered for the connection.
     *
     * @example username() ==> string
     *          username($username) ==> self
     *
     * @param string $username
     *
     * @return string|self
     */
    public function username($username = null)
    {
        return $this->dynamic('username', $username);
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
}
