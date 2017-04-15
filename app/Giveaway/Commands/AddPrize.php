<?php

namespace App\Giveaway\Commands;

use App\Giveaway\Entities\Prize;
use App\Giveaway\Messages\UpdatePrizes;
use ArtisanSDK\Server\Entities\Command;

class AddPrize extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->prize = array_get($arguments, 'prize', []);
    }

    /**
     * Run the command.
     */
    public function run()
    {
        // Add the prize to the prize pool
        $prizes = $this->dispatcher()->prizes();
        $prizes->push(new Prize(array_get($this->prize, 'name'), array_get($this->prize, 'sponsor')));

        // Notify all the connections of the new prize
        return $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes));
    }
}
