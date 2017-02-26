<?php

namespace App\Server\Commands;

use App\Server\Contracts\Listener;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\DynamicProperties;
use Exception;
use Illuminate\Support\Fluent;

class CommandException extends Fluent implements ServerCommand
{
    use DynamicProperties;

    protected $listener;

    /**
     * Wrap an exception as a command.
     *
     * @param \Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = get_class($exception);
        $this->message   = $exception->getMessage();
        $this->code      = $exception->getCode() ? $exception->getCode() : 400;
    }

    /**
     * Get or set the connection listener.
     *
     * @param \App\Server\Contracts\Listener $interface for the server
     *
     * @return \App\Server\Contracts\Listener|self
     */
    public function listener(Listener $interface = null)
    {
        return $this->dynamic('listener', $interface);
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
    }
}
