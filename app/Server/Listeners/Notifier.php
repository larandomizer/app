<?php

namespace App\Server\Listeners;

use App\Server\Entities\Listener as BaseListener;

class Notifier extends BaseListener
{
    /**
     * Initialize any registered message handlers upon construction.
     *
     * @return self
     */
    public function boot()
    {
        $this->register(\App\Server\Messages\DismissNotifications::class, \App\Server\Commands\DismissNotifications::class);
        $this->register(\App\Server\Messages\NotifyConnection::class, \App\Server\Commands\NotifyConnection::class);

        return $this;
    }
}
