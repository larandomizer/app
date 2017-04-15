<?php

namespace App\Giveaway\Commands;

use App\Giveaway\Messages\AwardWinner;
use App\Giveaway\Messages\UpdatePrizes;
use ArtisanSDK\Server\Contracts\Connection;
use ArtisanSDK\Server\Entities\Command;
use ArtisanSDK\Server\Messages\UpdateConnections;

class PickRandomWinner extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $everyone = $this->dispatcher()->connections();
        $prizes = $this->dispatcher()->prizes();

        // Get the first prize that is available
        $prize = $prizes->available()->first();
        if ( ! $prize) {
            return;
        }

        // Pick a random connection as the winner
        $winner = $this->dispatcher()
            ->connections()
            ->types(Connection::PLAYER)
            ->random();

        // Award the prize to the winner
        $winner->type(Connection::WINNER);
        $winner->prize($prize);
        $prize->winner($winner->uuid())->awarded(true);

        // Make everyone else a loser if there are no more prizes
        if ( ! $prizes->available()->count()) {
            $everyone->types(Connection::PLAYER)->each(function ($connection) {
                $connection->type(Connection::LOSER);
            });
        }

        // Update the connections with the prize winner and losers
        $this->dispatcher()
            ->broadcast(new UpdateConnections($everyone))
            ->broadcast(new UpdatePrizes($prizes))
            ->send(new AwardWinner($prize), $winner);
    }
}
