<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ServerCommand;
use App\Server\Notifications;

class UpdateNotifications extends Command implements ServerCommand
{
    /**
     * Send a list of all the notifications.
     *
     * @param \App\Server\Notifications $notifications
     */
    public function __construct(Notifications $notifications)
    {
        $this->notifications = $notifications->toArray();
    }
}
