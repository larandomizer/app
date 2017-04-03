<?php

namespace App\Server\Listeners;

use App\Server\Entities\Listener as BaseListener;

class ServerAdmin extends BaseListener
{
    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot()
    {
        $this->register(\App\Server\Messages\StopServer::class, \App\Server\Commands\StopServer::class);

        return $this;
    }
}
