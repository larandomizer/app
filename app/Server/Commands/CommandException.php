<?php

namespace App\Server\Commands;

use App\Server\Contracts\ServerCommand;
use Illuminate\Support\Fluent;

class CommandException extends Fluent implements ServerCommand
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
        $this->code      = $exeption->getCode() ? $exception->getCode() : 400;
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
    }
}
