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
use InvalidArgumentException;
use Ratchet\MessageComponentInterface as RatchetInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Broker implements BrokerInterface, LoggerInterface, RatchetInterface
{
    use FluentProperties, RatchetAdapter;

    protected $logger;
    protected $logging = true;
    protected $manager;
    protected $max_connections;

    /**
     * Inject and setup the dependencies.
     *
     * @param \App\Server\Manager                               $manager
     * @param \Symfony\Component\Console\Output\OutputInterface $logger
     */
    public function __construct(Manager $manager, OutputInterface $logger = null)
    {
        $this->manager($manager);
        $this->logger($logger);
    }

    /**
     * Get or set the manager interface that controls the event loop application.
     *
     * @example manager() ==> \App\Server\Contracts\Manager
     *          manager($interface) ==> self
     *
     * @param \App\Server\Contracts\Manager $interface
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function manager(Manager $interface = null)
    {
        return $this->property(__METHOD__, $interface);
    }

    /**
     * Get or set the maximum number of connections the server allows to connect.
     *
     * @example maxConnections() ==> 100
     *          maxConnections(100) ==> self
     *
     * @param int $number of maximium connections allowed to connect
     *
     * @return int|self
     */
    public function maxConnections($number = null)
    {
        return $this->property(snake_case(__METHOD__), $number);
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
            $message = $this->resolveMessage($message);
            $this->manager()->receive($message, $connection);
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
        return $this->property(__METHOD__, $interface);
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
        return $this->property(__METHOD__, $enable);
    }

    /**
     * Log to the output. @todo could use some refactoring.
     *
     * @param mixed $message that can be cast to a string
     *
     * @return self
     */
    public function log($message)
    {
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
     * Parse the message arguments into a message entity.
     *
     * @param string $message to parse
     *
     * @return \App\Server\Contracts\Message
     */
    protected function resolveMessage($message)
    {
        $arguments = (array) json_decode($message, true);
        $name = array_get($arguments, 'name');
        $class = str_replace(class_basename($this), 'Messages\\'.$name, get_class($this));
        if ( ! class_exists($class)) {
            throw new InvalidArgumentException($class.' does not exist.');
        }

        return new $class($arguments);
    }
}
