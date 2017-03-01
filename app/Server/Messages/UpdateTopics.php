<?php

namespace App\Server\Messages;

use App\Server\Contracts\ServerMessage;
use App\Server\Entities\Message;
use App\Server\Entities\Topics;

class UpdateTopics extends Message implements ServerMessage
{
    /**
     * Send a list of all the topics.
     *
     * @param \App\Server\Entities\Topics $topics
     */
    public function __construct(Topics $topics)
    {
        $this->topics = $topics->toArray();
    }
}
