<?php

namespace App\Giveaway\Commands;

use App\Giveaway\Entities\Prizes;
use App\Giveaway\Messages\UpdatePrizes;
use App\Server\Contracts\Connection;
use App\Server\Entities\Command;
use App\Server\Messages\UpdateConnections;

class ResetPrizes extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $this->dispatcher()->prizes(new Prizes());

        $prizes = $this->dispatcher()->prizes();
        $everyone = $this->dispatcher()->connections();

        $everyone->types([Connection::LOSER, Connection::WINNER])
            ->each(function ($connection) {
                $connection->type(Connection::PLAYER);
            });

        return $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes))
            ->broadcast(new UpdateConnections($everyone));
    }
}
