<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;
use App\Server\Entities\Prizes;

class UpdatePrizes extends Message implements ServerMessage
{
    /**
     * Send a list of all the prizes.
     *
     * @param \App\Server\Entities\Prizes $prizes
     */
    public function __construct(Prizes $prizes)
    {
        $this->prizes = $prizes->toArray();
    }
}
