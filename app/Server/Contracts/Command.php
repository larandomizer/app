<?php

namespace App\Server\Contracts;

use Illuminate\Contracts\Support\Jsonable;

interface Command extends Jsonable
{
    /**
     * Get or set the connection listener.
     *
     * @param \App\Server\Contracts\Listener $interface for the server
     *
     * @return \App\Server\Contracts\Listener|self
     */
    public function listener(Listener $interface = null);

    /**
     * Handle the command.
     */
    public function handle();
}
