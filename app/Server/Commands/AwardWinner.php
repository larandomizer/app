<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\Prize;
use App\Server\Contracts\ServerCommand;

class AwardWinner extends Command implements ServerCommand
{
    /**
     * Tell the winner what they won.
     *
     * @param \App\Server\Contracts\Prize $prize
     */
    public function __construct(Prize $prize)
    {
        $this->prize = $prize->toArray();
    }
}
