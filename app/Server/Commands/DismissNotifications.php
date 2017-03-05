<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;
use App\Server\Entities\Notifications;
use App\Server\Messages\UpdateNotifications;

class DismissNotifications extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->uuid = array_get($arguments, 'uuid');
    }

    /**
     * Run the command.
     *
     * @return mixed
     */
    public function run()
    {
        $connection = $this->dispatcher()
            ->connections()
            ->uuid($this->uuid);

        $connection->notifications(new Notifications());

        return $this->dispatcher()
            ->send(new UpdateNotifications($connection->notifications()), $connection);
    }
}
