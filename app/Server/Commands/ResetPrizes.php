<?php

namespace App\Server\Commands;

use App\Server\Contracts\Connection;
use App\Server\Entities\Command;
use App\Server\Entities\Prizes;
use App\Server\Messages\UpdateConnections;
use App\Server\Messages\UpdatePrizes;

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

        $everyone->types([CONNECTION::LOSER, CONNECTION::WINNER])
            ->each(function ($connection) {
                $connection->type(CONNECTION::PLAYER);
            });

        return $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes), $everyone)
            ->broadcast(new UpdateConnections($everyone), $everyone);
    }
}
