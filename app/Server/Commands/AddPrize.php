<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use App\Server\Prize;
use App\Server\Traits\AdminProtection;

class AddPrize extends Command implements ClientCommand, ServerCommand
{
    use AdminProtection;

    /**
     * Save the command arguments for later when the command is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->name = array_get($arguments, 'name');
        $this->sponsor = array_get($arguments, 'sponsor');
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        $prize = new Prize($this->name, $this->sponsor);

        $this->listener()->prizes()->push($prize);

        return $this->listener()
            ->broadcast(
                new UpdatePrizes($this->listener()->prizes()),
                $this->listener()->connections()
            );
    }
}
