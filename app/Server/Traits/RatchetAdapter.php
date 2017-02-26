<?php

namespace App\Server\Traits;

use Ratchet\ConnectionInterface;

trait RatchetAdapter
{
    /**
     * When a new connection is opened it will be passed to this method.
     *
     * @param \Ratchet\ConnectionInterface $conn The socket/connection that just connected to your application
     *
     * @throws \Exception
     *
     * @return self
     */
    public function onOpen(ConnectionInterface $conn)
    {
        return $this->open(new Connection($conn));
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).
     * SendMessage to $conn will not result in an error if it has already been closed.
     *
     * @param \Ratchet\ConnectionInterface $conn The socket/connection that is closing/closed
     *
     * @throws \Exception
     *
     * @return self
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->close($this->connections()->socket($conn));
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method.
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param \Exception                   $e
     *
     * @throws \Exception
     *
     * @return self
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->error($this->connections()->socket($conn), $e);
    }

    /**
     * Triggered when a client sends data through the socket.
     *
     * @param \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param string                       $msg  The message received
     *
     * @throws \Exception
     *
     * @return self
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->message($this->connections()->socket($from), $msg);
    }
}
