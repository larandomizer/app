<?php

namespace App\Server\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class DisconnectAll extends Message implements ClientMessage
{
    use AdminProtection;
}
