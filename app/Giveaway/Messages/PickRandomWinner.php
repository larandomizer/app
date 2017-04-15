<?php

namespace App\Giveaway\Messages;

use ArtisanSDK\Server\Contracts\ClientMessage;
use ArtisanSDK\Server\Entities\Message;
use ArtisanSDK\Server\Traits\AdminProtection;

class PickRandomWinner extends Message implements ClientMessage
{
    use AdminProtection;
}
