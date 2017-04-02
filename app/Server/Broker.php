<?php

namespace App\Server;

use App\Server\Contracts\Broker as BrokerInterface;
use App\Server\Contracts\Connection;
use App\Server\Contracts\Logger as LoggerInterface;
use App\Server\Contracts\Message;
use App\Server\Entities\Connections;
use App\Server\Messages\MessageException;
use App\Server\Traits\FluentProperties;
use App\Server\Traits\RatchetAdapter;
use Exception;
use Ratchet\MessageComponentInterface as RatchetInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Broker implements BrokerInterface, LoggerInterface, RatchetInterface
{
    use FluentProperties, RatchetAdapter;

    protected $logger;
    protected $logging = true;

    /**
     * Inject and setup the dependencies.
     *
     * @param \App\Server\Manager                               $manager
     * @param \Symfony\Component\Console\Output\OutputInterface $logger
     */
    public function __construct(OutputInterface $logger = null)
    {
        $this->logger($logger);
    }

    /**
     * Get the manager interface that controls the event loop application.
     *
     * @return \App\Server\Contracts\Manager
     */
    public function manager()
    {
        return Server::instance()->manager();
    }

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection)
    {
        if ($this->maxConnections() > 0
            && $this->manager()->connections()->count() >= $this->maxConnections()) {
            $exception = new Exception('Connection refused because server has reached its limit for new connections.');

            return $this->end(new MessageException($exception), $connection);
        }

        $this->manager()->open($connection);

        return $this;
    }

    /**
     * Send message to one connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function send(Message $message, Connection $connection)
    {
        $connection->send($message->toJson());

        if ($this->logging()) {
            $this->log($message);
        }

        return $this;
    }

    /**
     * Send message to one connection and then close the connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function end(Message $message, Connection $connection)
    {
        $this->send($message, $connection);

        $connection->close();

        return $this;
    }

    /**
     * Broadcast message to multiple connections.
     *
     * @param \App\Server\Contracts\Message    $message
     * @param \App\Server\Entities\Connections $connections to send to
     *
     * @return self
     */
    public function broadcast(Message $message, Connections $connections)
    {
        $original = $this->logging();
        $this->logging(false);

        $connections->each(function ($connection) use ($message) {
            $this->send($message, $connection);
        });

        $this->logging($original);

        if ($this->logging()) {
            $this->log($message);
        }

        return $this;
    }

    /**
     * Called when a new message is received from an open connection.
     *
     * @param \App\Server\Contracts\Connection $connection sending the message
     * @param string                           $message    payload received
     *
     * @return self
     */
    public function message(Connection $connection, $message)
    {
        if ($this->logging()) {
            $this->log($message);
        }

        try {
            $this->manager()->receive($this->resolveMessage($message), $connection);
        } catch (Exception $exception) {
            $this->end(new MessageException($exception), $connection);
        }

        return $this;
    }

    /**
     * Called when an open connection is closed.
     *
     * @param \App\Server\Contracts\Connection $connection to be closed
     *
     * @return self
     */
    public function close(Connection $connection)
    {
        $this->manager()->close($connection);

        if ($this->logging()) {
            $this->log($connection);
        }

        return $this;
    }

    /**
     * Called when an error occurs on the connection.
     *
     * @param \App\Server\Contracts\Connection $connection that errored
     * @param \Exception                       $exception  caught
     *
     * @return self
     */
    public function error(Connection $connection, Exception $exception)
    {
        $this->manager()->error($connection, $exception);

        if ($this->logging()) {
            $this->log($exception);
        }

        return $this;
    }

    /**
     * Get or set the output interface the server logs output to.
     *
     * @example logger() ==> \Symfony\Component\Console\Output\OutputInterface
     *          logger($interface) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function logger(OutputInterface $interface = null)
    {
        return $this->property(__FUNCTION__, $interface);
    }

    /**
     * Get or set if the broker should log anything.
     *
     * @example logging() ==> true
     *          logging(true) ==> self
     *
     * @param bool $enable
     *
     * @return bool|self
     */
    public function logging($enable = null)
    {
        return $this->property(__FUNCTION__, $enable);
    }

    /**
     * Log to the output.
     *
     * @param mixed $message that can be cast to a string
     *
     * @return self
     */
    public function log($message)
    {
        if ( ! $this->logger()) {
            return $this;
        }

        if ($message instanceof Exception) {
            $this->logger()->writeln($message->getMessage());

            return $this;
        }

        if ($message instanceof Connection) {
            $this->logger()->writeln($message->toJson());

            return $this;
        }

        if ($message instanceof Message) {
            $this->logger()->writeln($message->toJson());

            return $this;
        }

        if (is_array($message)) {
            $this->logger()->writeln(json_encode($message));

            return $this;
        }

        if (is_string($message)) {
            $this->logger()->writeln($message);

            return $this;
        }

        return $this;
    }

    /**
     * Get the maximum connections allowed.
     *
     * @return int
     */
    protected function maxConnections()
    {
        return Server::instance()->config(snake_case(__FUNCTION__));
    }

    /**
     * Parse the message arguments into a message entity.
     *
     * @param string $message to parse
     *
     * @throws \InvalidArgumentException if message is not a ClientMessage
     *
     * @return \App\Server\Contracts\Message
     */
    protected function resolveMessage($message)
    {
        $arguments = (array) json_decode($message, true);
        $name = array_get($arguments, 'name');
        $class = $this->resolveMessageClass($name);

        return new $class($arguments);
    }

    /**
     * Resolve the message class from the name by searching in the paths.
     *
     * @param string $name of class
     *
     * @throws \InvalidArgumentException if class cannot be found in path.
     *
     * @return string
     */
    protected function resolveMessageClass($name)
    {
        foreach ((array) Server::instance()->namespaces() as $namespace) {
            $class = $namespace.$name;
            if (class_exists($class)) {
                return $class;
            }
        }

        throw new InvalidArgumentException($name.' message does not exist in the namespaces.');
    }
}
