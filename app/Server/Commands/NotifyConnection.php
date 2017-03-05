<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;
use App\Server\Entities\Notification;
use App\Server\Messages\UpdateNotifications;

class NotifyConnection extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->receiver = array_get($arguments, 'receiver');
        $this->sender = array_get($arguments, 'sender');
    }

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run()
    {
        $everyone = $this->dispatcher()
            ->connections();

        $receiver = $everyone->uuid($this->receiver);

        $notification = new Notification($this->sender);
        $notifications = $receiver->notifications();
        $notifications->put($notification->sender(), $notification);

        return $this->dispatcher()
            ->send(new UpdateNotifications($notifications), $receiver);
    }
}
