<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ServerCommand;
use Exception;

class CommandException extends Command implements ServerCommand
{
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
}
