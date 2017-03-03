<?php

namespace App\Server\Messages;

use App\Server\Commands\ResetPrizes as ResetPrizesCommand;
use App\Server\Contracts\ClientMessage;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class ResetPrizes extends Message implements ClientMessage
{
    use AdminProtection;

    /**
     * Handle the message.
     */
    public function handle()
    {
        return $this->dispatcher()->run(new ResetPrizesCommand());
    }
}
