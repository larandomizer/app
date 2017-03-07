<?php

namespace App\Server\Commands;

use App\Server\Contracts\Connection;
use App\Server\Entities\Command;
use App\Server\Messages\AwardWinner;
use App\Server\Messages\UpdateConnections;
use App\Server\Messages\UpdatePrizes;

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
            $everyone->types(CONNECTION::PLAYER)->each(function ($connection) {
                $connection->type(CONNECTION::LOSER);
            });
        }

        $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes),$everyone)
            ->broadcast(new UpdateConnections($everyone), $everyone)
            ->send(new AwardWinner($prize), $winner);
    }
}
