<?php

namespace App\Server\Entities;

use App\Server\Contracts\Command as CommandInterface;
use App\Server\Contracts\Listener as ListenerInterface;
use App\Server\Contracts\Manager;
use App\Server\Contracts\Message as MessageInterface;
use App\Server\Traits\FluentProperties;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use ReflectionClass;

class Listener implements ListenerInterface
{
    use FluentProperties;

    protected $dispatcher;
    protected $handlers;

    /**
     * Setup listener and inject dependencies.
     *
     * @param \Illuminate\Support\Collection $handlers
     *
     * @return self
     */
    public function __construct(Collection $handlers = null)
    {
        $this->handlers = $handlers ?: new Collection();
        $this->boot();
    }

    /**
     * Make an instance of the listener.
     *
     * @return \App\Server\Contracts\Listener
     */
    public static function make()
    {
        return new static();
    }

    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot()
    {
        return $this;
    }

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Pass the message to the command handlers that are listening
     * for the message to be received.
     *
     * @param \App\Serer\Contracts\Message $message
     *
     * @return self
     */
    public function handle(MessageInterface $message)
    {
        $this->commands($message)
            ->each(function ($command) use ($message) {
                $this->dispatcher()
                    ->run(new $command($message->toArray()));
            });

        return $this;
    }

    /**
     * Get the commands registered for this listener.
     *
     * Optional argument filters the collection of commands to those
     * registered for that message.
     *
     * @param \App\Server\Contracts\Message|string|null $message
     *
     * @return \Illuminate\Support\Collection
     */
    public function commands($message = null)
    {
        if (is_null($message)) {
            return $this->handlers->flatten();
        }

        $message = $this->resolveToClassname($message);
        $this->checkType($message, MessageInterface::class);

        return $this->handlers->get($message, new $this->handlers());
    }

    /**
     * Get the messages registered for this listener.
     *
     * Optional argument filters the collection of messages to those
     * registered for that command.
     *
     * @param \App\Server\Contracts\Command|string|null $command
     *
     * @return \Illuminate\Support\Collection
     */
    public function messages($command = null)
    {
        $collection = $this->handlers;

        if (is_null($command)) {
            return $collection->keys();
        }

        $command = $this->resolveToClassname($command);
        $this->checkType($command, CommandInterface::class);

        $messages = [];
        $collection->each(
            function ($commands, $key) use (&$messages, $command) {
                if ($commands->where($command)->count()) {
                    $messages[] = $message;
                }
            });


        return new $collection($messages);
    }

    /**
     * Register a command to handle the message.
     *
     * @param \App\Server\Contracts\Message|string $message to listen for
     * @param \App\Server\Contracts\Command|string $command to invoke for message
     *
     * @return self
     */
    public function register($message, $command)
    {
        $message = $this->resolveToClassname($message);
        $this->checkType($message, MessageInterface::class);

        $command = $this->resolveToClassname($command);
        $this->checkType($command, CommandInterface::class);

        $commands = $this->handlers->get($message, new $this->handlers());
        $commands->push($command);

        $this->handlers->put($message, $commands);

        return $this;
    }

    /**
     * Unregister a message handler entirely or for a single command.
     *
     * @param \App\Server\Contracts\Message|string $message that is being listened to
     * @param \App\Server\Contracts\Command|string $command to unregister
     *
     * @return self
     */
    public function unregister($message, $command = null)
    {
        $message = $this->resolveToClassname($message);
        $this->checkType($message, MessageInterface::class);

        if (is_null($command)) {
            $this->handlers->forget($message);

            return $this;
        }

        $command = $this->resolveToClassname($command);
        $this->checkType($command, CommandInterface::class);

        $commands = $this->handlers->get($message);
        $command->pull($command);

        return $this;
    }

    /**
     * Resolve the message to a class name.
     *
     * @param \App\Server\Contracts\Message|string $message
     *
     * @return string
     */
    protected function resolveToClassname($message)
    {
        return is_string($message) ? $message : get_class($message);
    }

    /**
     * Check that the argument is a certain type.
     *
     * @param string $argument that should be the type
     * @param string $type     to check against
     *
     * @throws \InvalidArgumentException if argument is not the type
     */
    protected function checkType($argument, $type)
    {
        $instance = new ReflectionClass($argument);
        if ( ! $instance->implementsInterface($type)) {
            throw new InvalidArgumentException('Expecting argument "'.$argument.'" to be an instance of "'.$type.'".');
        }
    }
}
