<?php

namespace App\Giveaway\Messages;

use App\Giveaway\Entities\Prizes;
use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;

class UpdatePrizes extends Message implements ServerMessage
{
    /**
     * Send a list of all the prizes.
     *
     * @param \App\Giveaway\Entities\Prizes $prizes
     */
    public function __construct(Prizes $prizes)
    {
        $this->prizes = $prizes->toArray();
    }
}
