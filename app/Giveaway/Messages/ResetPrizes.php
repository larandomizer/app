<?php

namespace App\Giveaway\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class ResetPrizes extends Message implements ClientMessage
{
    use AdminProtection;
}
