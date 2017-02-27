<?php

namespace App\Server\Contracts;

use App\Server\Connections;
use App\Server\Prizes;
use Exception;

interface Listener
{
    /**
     * Called when the server is started.
     *
     * @return self
     */
    public function start();

    /**
     * Called when the server is stopped.
     *
     * @return self
     */
    public function stop();

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection);

    /**
     * Send command to one connection.
     *
     * @param \App\Server\Contracts\Command    $command    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function send(Command $command, Connection $connection, $silent = false);

    /**
     * Send command to one connection and then close the connection.
     *
     * @param \App\Server\Contracts\Command    $command    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function end(Command $command, Connection $connection, $silent = false);

    /**
     * Broadcast command to multiple connections.
     *
     * @param \App\Server\Contracts\Command $command
     * @param \App\Server\Connections       $connections to send to
     * @param bool                          $silent      output
     *
     * @return self
     */
    public function broadcast(Command $command, Connections $connections, $silent = false);

    /**
     * Called when a new message is received from an open connection.
     *
     * @param \App\Server\Contracts\Connection $connection sending the message
     * @param string                           $message    payload received
     *
     * @return self
     */
    public function message(Connection $connection, $message, $silent = false);

    /**
     * Called when an open connection is closed.
     *
     * @param \App\Server\Contracts\Connection $connection to be closed
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function close(Connection $connection, $silent = false);

    /**
     * Called when an error occurs on the connection.
     *
     * @param \App\Server\Contracts\Connection $connection that errored
     * @param \Exception                       $exception  caught
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function error(Connection $connection, Exception $exception, $silent = false);

    /**
     * Get or set the connections on the server.
     *
     * @example connections() ==> \App\Server\Connections
     *          connections($connections) ==> self
     *
     * @param \App\Server\Connections $connections
     *
     * @return \App\Server\Connections|self
     */
    public function connections(Connections $connections = null);

    /**
     * Get or set the prizes on the server.
     *
     * @example prizes() ==> \App\Server\Prizes
     *          prizes($prizes) ==> self
     *
     * @param \App\Server\Prizes $prizes
     *
     * @return \App\Server\Prizes|self
     */
    public function prizes(Prizes $prizes = null);

    /**
     * Log to the output. @todo could use some refactoring.
     *
     * @param mixed $message that can be cast to a string
     *
     * @return self
     */
    public function log($message);
}
