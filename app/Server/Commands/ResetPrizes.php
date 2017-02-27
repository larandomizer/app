<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\AdminProtection;

class ResetPrizes extends Command implements ClientCommand, ServerCommand
{
    use AdminProtection;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $this->listener()->prizes([]);

        return $this->listener()
            ->broadcast(
                new UpdatePrizes($this->listener()->prizes()),
                $this->listener()->connections()
            );
    }
}
