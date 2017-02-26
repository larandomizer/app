<?php

namespace App\Server\Contracts;

use Ratchet\ConnectionInterface;

interface Connection extends ConnectionInterface
{
    const ANONYMOUS  = 'anonymous';
    const PLAYER     = 'player';
    const SPECATOTOR = 'spectator';
    const WINNER     = 'winner';

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
     * Get or set the username registered for the connection.
     *
     * @example username() ==> string
     *          username($username) ==> self
     *
     * @param string $username
     *
     * @return string|self
     */
    public function username($username = null);

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
