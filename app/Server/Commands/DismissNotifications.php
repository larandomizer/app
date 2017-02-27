<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\ClientProtection;

class DismissNotifications extends Command implements ClientCommand, ServerCommand
{
    use ClientProtection;

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
        if ( ! $this->client()) {
            $this->client($this->listener()
                ->connections()
                ->uuid($this->uuid));
        }

        if ($this->authorize()) {
            $this->client()->notifications([]);

            return $this->listener()
                ->send(
                    new UpdateNotifications($this->client()->notifications()),
                    $this->client()
                );
        }
    }
}
