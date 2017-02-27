<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Contracts\Connection;
use App\Server\Contracts\ServerCommand;
use App\Server\Traits\AdminProtection;

class PickRandomWinner extends Command implements ClientCommand, ServerCommand
{
    use AdminProtection;

    /**
     * Handle the command.
     */
    public function handle()
    {
        $prize = $this->listener()
            ->prizes()
            ->first();

        $winner = $this->listener()
            ->connections()
            ->type(Connection::PLAYER)
            ->random();

        $winner->type(Connection::WINNER);
        $winner->prize($prize);

        $this->listener()
            ->prizes()
            ->forget($prize);

        $this->listener()
            ->broadcast(
                new UpdatePrizes($this->listener()->prizes()),
                $this->listener()->connections())
            ->broadcast(
                new UpdateConnections($this->listener()->connections()),
                $this->listener()->connections())
            ->send(new AwardWinner($prize), $winner);
    }
}
