<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;
use App\Server\Entities\Notifications;

class UpdateNotifications extends Message implements ServerMessage
{
    /**
     * Send a list of all the notifications.
     *
     * @param \App\Server\Entities\Notifications $notifications
     */
    public function __construct(Notifications $notifications)
    {
        $this->notifications = $notifications->toArray();
    }
}
