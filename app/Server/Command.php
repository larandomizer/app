<?php

namespace App\Server;

use App\Server\Contracts\Command as CommandInterface;
use App\Server\Contracts\Listener;
use App\Server\Traits\DynamicProperties;
use Illuminate\Support\Fluent;

abstract class Command extends Fluent implements CommandInterface
{
    use DynamicProperties;

    protected $listener;

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
     *
     * @return  mixed
     */
    public function handle()
    {
        return $this->toArray();
    }
}