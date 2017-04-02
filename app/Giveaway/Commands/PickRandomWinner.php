<?php

namespace App\Giveaway\Commands;

use App\Giveaway\Messages\AwardWinner;
use App\Giveaway\Messages\UpdatePrizes;
use App\Server\Contracts\Connection;
use App\Server\Entities\Command;
use App\Server\Messages\UpdateConnections;

class PickRandomWinner extends Command
{
    /**
     * Run the command.
     */
    public function run()
    {
        $prizes = $this->dispatcher()->prizes();

        $prize = $prizes->available()->first();
        if ( ! $prize) {
            return;
        }

        $winner = $this->dispatcher()
            ->connections()
            ->types(Connection::PLAYER)
            ->random();

        $winner->type(Connection::WINNER);
        $winner->prize($prize);
        $prize->winner($winner->uuid())->awarded(true);

        $everyone = $this->dispatcher()->connections();

        if ( ! $prizes->available()->count()) {
            $everyone->types(Connection::PLAYER)->each(function ($connection) {
                $connection->type(Connection::LOSER);
            });
        }

        $this->dispatcher()
            ->broadcast(new UpdateConnections($everyone))
            ->broadcast(new UpdatePrizes($prizes))
            ->send(new AwardWinner($prize), $winner);
    }
}
