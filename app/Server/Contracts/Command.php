<?php

namespace App\Server\Contracts;

use Illuminate\Contracts\Support\Jsonable;

interface Command extends Jsonable
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = []);

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);

    /**
     * Get or set the delay in milliseconds for the command to be executed.
     *
     * @param int $delay in milliseconds
     *
     * @return int|self
     */
    public function delay($delay = null);

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run();
}
