<?php

namespace App\Server\Entities;

use App\Server\Contracts\Command;
use App\Server\Contracts\Listener;
use App\Server\Contracts\Message;
use Illuminate\Support\Collection;

class Listeners extends Collection
{
    /**
     * Add a listener to the collection.
     *
     * @param App\Server\Contracts\Listener $listener
     *
     * @return self
     */
    public function add(Listener $listener)
    {
        $this->push($listener);

        return $this;
    }

    /**
     * Remove a listener from the collection.
     *
     * @param App\Server\Contracts\Listener $listener
     *
     * @return self
     */
    public function remove(Listener $listener)
    {
        $index = array_search($this->items, $listener, $strict = true);
        if ($index === false) {
            $this->offsetUnset($index);
        }

        return $this;
    }

    /**
     * Filter collection of listeners to those listening for the message.
     *
     * @param \App\Server\Contracts\Message $message
     *
     * @return self
     */
    public function forMessage(Message $message)
    {
        return $this->filter(function ($listener) use ($message) {
            return $listener->commands($message)->count();
        });
    }

    /**
     * Filter collection of listeners to those with the command handler.
     *
     * @param \App\Server\Contracts\Command $command
     *
     * @return self
     */
    public function forCommand(Command $command)
    {
        return $this->filter(function ($listener) use ($command) {
            return $listener->messages($command)->count();
        });
    }

    /**
     * Pass the message through all of the listeners.
     *
     * @param \App\Server\Contracts\Message $message
     *
     * @return self
     */
    public function handle(Message $message)
    {
        return $this->forMessage($message)
            ->each(function ($listener) use ($message) {
                $listener->handle($message);
            });
    }
}
