<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use App\Server\Notification;
use App\Server\Traits\NoProtection;

class NotifyConnection extends Command implements ClientCommand, ServerCommand
{
    use NoProtection;

    /**
     * Save the command arguments for later when the command is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->uuid = array_get($arguments, 'uuid');
    }

    /**
     * Handle the command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notification = new Notification($this->client()->uuid());

        $connection = $this->listener()
            ->connections()
            ->uuid($uuid);

        $connection->notifications()
            ->put($notification->sender(), $notification);

        return $this->listener()
            ->send(
                new UpdateNotifications($connection->notifications()),
                $connection
            );
    }
}
