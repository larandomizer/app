<?php

namespace App\Server\Commands;

use App\Server\Contracts\Connection;
use App\Server\Entities\Command;
use App\Server\Messages\ConnectionRegistered;
use App\Server\Messages\UpdateConnections;

class RegisterConnection extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->connection = array_get($arguments, 'connection', []);
    }

    /**
     * Run the command.
     */
    public function run()
    {
        $everyone = $this->dispatcher() ->connections();

        $connection = $everyone->uuid(array_get($this->connection, 'uuid'));

        $name = array_only($this->connection, ['first_name', 'last_name']);
        $name = trim(implode(' ', $name));

        $connection->name($name ?: 'Anonymous')
            ->email(array_get($this->connection, 'email', 'Not Available'))
            ->type(array_get($this->connection, 'type', Connection::ANONYMOUS));

        return $this->dispatcher()
            ->send(new ConnectionRegistered($connection), $connection)
            ->broadcast(new UpdateConnections($everyone), $everyone);
    }
}
