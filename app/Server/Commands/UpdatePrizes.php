<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ServerCommand;
use App\Server\Prizes;

class UpdatePrizes extends Command implements ServerCommand
{
    /**
     * Send a list of all the prizes.
     *
     * @param \App\Server\Prizes $prizes
     */
    public function __construct(Prizes $prizes)
    {
        $this->prizes = $prizes->toArray();
    }
}
