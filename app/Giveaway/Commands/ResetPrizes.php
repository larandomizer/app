<?php

namespace App\Giveaway\Commands;

use App\Giveaway\Entities\Prizes;
use App\Giveaway\Messages\UpdatePrizes;
use ArtisanSDK\Server\Contracts\Connection;
use ArtisanSDK\Server\Entities\Command;
use ArtisanSDK\Server\Messages\UpdateConnections;

class ResetPrizes extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        // Reset the prizes
        $this->dispatcher()->prizes(new Prizes());
        $prizes = $this->dispatcher()->prizes();

        // Make everyone a player again
        $everyone = $this->dispatcher()->connections();
        $everyone->types([Connection::LOSER, Connection::WINNER])
            ->each(function ($connection) {
                $connection->type(Connection::PLAYER);
            });

        // Update all the connections with the new details
        return $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes))
            ->broadcast(new UpdateConnections($everyone));
    }
}
