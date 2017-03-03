<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;
use App\Server\Entities\Prizes;
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

        return $this->dispatcher()
            ->broadcast(new UpdatePrizes($prizes), $everyone);
    }
}
