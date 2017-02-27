<?php

namespace App\Server\Contracts;

use App\Server\Notifications;
use Ratchet\ConnectionInterface;

interface Connection extends ConnectionInterface
{
    const ANONYMOUS = 'anonymous';
    const PLAYER    = 'player';
    const SPECTATOR = 'spectator';
    const WINNER    = 'winner';

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
    public function socket(ConnectionInterface $interface = null);

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
    public function notifications(Notifications $collection = null);

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
    public function uuid($uuid = null);

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
    public function type($type = null);

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
    public function email($email = null);

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
    public function name($name = null);

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
    public function prize(Prize $prize = null);

    /**
     * Send data to the connection.
     *
     * @param string $data
     *
     * @return \App\Server\Contracts\Connection
     */
    public function send($data);

    /**
     * Close the connection.
     */
    public function close();
}
