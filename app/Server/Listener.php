<?php

namespace App\Server;

use App\Server\Commands\UpdatePrizes;
use App\Server\Commands\UpdateConnections;
use App\Server\Commands\CommandException;
use App\Server\Commands\PromptForAuthentication;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\Command;
use App\Server\Contracts\Connection;
use App\Server\Contracts\Listener as ListenerInterface;
use App\Server\Traits\DynamicProperties;
use App\Server\Traits\RatchetAdapter;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ratchet\MessageComponentInterface as RatchetInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Listener implements ListenerInterface, RatchetInterface
{
    use DynamicProperties, RatchetAdapter;

    protected $connections;
    protected $loop;
    protected $output;
    protected $prizes;

    /**
     * Inject and setup the dependencies.
     *
     * @param \App\Server\Connections                           $connections
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \App\Server\Prizes                                $prizes
     */
    public function __construct(Connections $connections, OutputInterface $output = null, Prizes $prizes = null)
    {
        $this->connections($connections);
        $this->output($output);
        $this->prizes($prizes ?: new Prizes());
    }

    /**
     * Called when the server is started.
     *
     * @return self
     */
    public function start()
    {
        return $this;
    }

    /**
     * Called when the server is stopped.
     *
     * @return self
     */
    public function stop()
    {
        return $this;
    }

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     */
    public function open(Connection $connection)
    {
        $this->connections()->put($connection->uuid(), $connection);

        $this->send(new UpdatePrizes($this->prizes()), $connection)
            ->broadcast(new UpdateConnections($this->connections()), $this->connections());

        return $this;
    }

    /**
     * Send command to one connection.
     *
     * @param \App\Server\Contracts\Command    $command    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function send(Command $command, Connection $connection, $silent = false)
    {
        $connection->send($command->id(Uuid::uuid4()->toString())
            ->name(class_basename($command))
            ->timestamp(microtime(true))
            ->toJson());

        if ( ! $silent) {
            $this->log($command);
        }

        return $this;
    }

    /**
     * Send command to one connection and then close the connection.
     *
     * @param \App\Server\Contracts\Command    $command    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function end(Command $command, Connection $connection, $silent = false)
    {
        $this->send($command, $connection, $silent);

        $connection->close();

        return $this;
    }

    /**
     * Broadcast command to multiple connections.
     *
     * @param \App\Server\Contracts\Command $command
     * @param \App\Server\Connections       $connections to send to
     * @param bool                          $silent      output
     *
     * @return self
     */
    public function broadcast(Command $command, Connections $connections, $silent = false)
    {
        $connections
            ->topic($command->topics())
            ->each(function ($connection) use ($command) {
                $this->send($command, $connection, true);
            });

        if ( ! $silent) {
            $this->log($command);
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
    public function message(Connection $connection, $message, $silent = false)
    {
        if ( ! $silent) {
            $this->log($message);
        }

        try {
            $command = $this->resolveClientCommand($message);
            $command->listener($this);

            if ( ! $command->client($connection)->authorize()) {
                return $this->send(new PromptForAuthentication($command), $connection, $silent);
            }

            $command->handle();
        } catch (Exception $exception) {
            $this->end(new CommandException($exception), $connection, $silent);
        }

        return $this;
    }

    /**
     * Called when an open connection is closed.
     *
     * @param \App\Server\Contracts\Connection $connection to be closed
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function close(Connection $connection, $silent = false)
    {
        $this->connections()->forget($connection);

        $this->broadcast(new UpdateConnections($this->connections()), $this->connections());

        if ( ! $silent) {
            $this->log($connection);
        }

        return $this;
    }

    /**
     * Called when an error occurs on the connection.
     *
     * @param \App\Server\Contracts\Connection $connection that errored
     * @param \Exception                       $exception  caught
     * @param bool                             $silent     output
     *
     * @return self
     */
    public function error(Connection $connection, Exception $exception, $silent = false)
    {
        $connection->close();

        if ( ! $silent) {
            $this->log($exception);
        }

        return $this;
    }

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
    public function connections(Connections $connections = null)
    {
        return $this->dynamic('connections', $connections);
    }

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
    public function prizes(Prizes $prizes = null)
    {
        return $this->dynamic('prizes', $prizes);
    }

    /**
     * Get or set the output interface the server logs output to.
     *
     * @example output() ==> \Symfony\Component\Console\Output\OutputInterface
     *          output($output) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function output(OutputInterface $interface = null)
    {
        return $this->dynamic('output', $interface);
    }

    /**
     * Get or set the event loop the server runs on.
     *
     * @example loop() ==> \React\EventLoop\LoopInterface
     *          loop($loop) ==> self
     *
     * @param \React\EventLoop\LoopInterface $interface
     *
     * @return \React\EventLoop\LoopInterface|self
     */
    public function loop(LoopInterface $interface = null)
    {
        return $this->dynamic('loop', $interface);
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
            $this->output()->writeln($message->toString());

            return $this;
        }

        if ($message instanceof Command) {
            $this->output()->writeln($message->toJson());

            return $this;
        }

        if (is_array($message)) {
            $this->output()->writeln(json_encode($message));
        }

        if (is_string($message)) {
            $this->output()->writeln($message);

            return $this;
        }

        return $this;
    }

    /**
     * Parse the message arguments into a command object.
     *
     * @param string $message to parse
     *
     * @return \App\Server\Contracts\Command
     */
    protected function resolveClientCommand($message)
    {
        $arguments = (array) json_decode($message, true);
        $name = array_get($arguments, 'name');
        $class = str_replace(class_basename($this), 'Commands\\'.$name, get_class($this));
        if ( ! class_exists($class)) {
            throw new InvalidArgumentException($class.' does not exist.');
        }

        $command = new $class($arguments);
        if ( ! $command instanceof ClientCommand) {
            throw new InvalidArgumentException($class.' is not a client command.');
        }

        return $command;
    }
}
