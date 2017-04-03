<?php

namespace App\Server\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Entities\Message;
use App\Server\Traits\ClientProtection;

class DismissNotifications extends Message implements ClientMessage
{
    use ClientProtection;

    /**
     * Save the message arguments for later when the message is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->uuid = array_get($arguments, 'connection');
    }
}
