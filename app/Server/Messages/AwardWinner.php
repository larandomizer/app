<?php

namespace App\Server\Messages;

use App\Server\Contracts\Prize;
use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;

class AwardWinner extends Message implements ServerMessage
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
