<?php

namespace App\Server\Listeners;

use App\Server\Commands\CloseConnections;
use App\Server\Entities\Listener as BaseListener;

class ConnectionPool extends BaseListener
{
    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot()
    {
        $this->register(\App\Server\Messages\DisconnectAll::class, CloseConnections::class);
        $this->register(\App\Server\Messages\DisconnectPlayers::class, CloseConnections::class);
        $this->register(\App\Server\Messages\DisconnectSpectators::class, CloseConnections::class);

        return $this;
    }
}
