<?php

namespace App\Server\Contracts;

interface Listener
{
    /**
     * Make an instance of the listener.
     *
     * @return \App\Server\Contracts\Listener
     */
    public static function make();

    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot();

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);

    /**
     * Pass the message to the command handlers that are listening
     * for the message to be received.
     *
     * @param \App\Serer\Contracts\Message $message
     *
     * @return self
     */
    public function handle(Message $message);

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
    public function commands($message = null);

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
    public function messages($command = null);

    /**
     * Register a command to handle the message.
     *
     * @param \App\Server\Contracts\Message|string $message to listen for
     * @param \App\Server\Contracts\Command|string $command to invoke for message
     *
     * @return self
     */
    public function register($message, $command);

    /**
     * Unregister a message handler entirely or for a single command.
     *
     * @param \App\Server\Contracts\Message|string $message that is being listened to
     * @param \App\Server\Contracts\Command|string $command to unregister
     *
     * @return self
     */
    public function unregister($message, $command = null);
}
