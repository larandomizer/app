<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;
use App\Server\Entities\Topics;

class UpdateSubscriptions extends Message implements ServerMessage
{
    /**
     * Send a list of all the subscriptions.
     *
     * @param \App\Server\Entities\Topics $topics
     */
    public function __construct(Topics $topics)
    {
        $this->subscriptions = $topics->toArray();
    }
}
