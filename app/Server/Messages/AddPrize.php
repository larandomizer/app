<?php

namespace App\Server\Messages;

use App\Server\Commands\AddPrize as AddPrizeCommand;
use App\Server\Contracts\ClientMessage;
use App\Server\Entities\Message;
use App\Server\Traits\AdminProtection;

class AddPrize extends Message implements ClientMessage
{
    use AdminProtection;

    /**
     * Save the message arguments for later when the message is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->prize = array_get($arguments, 'prize', []);
    }

    /**
     * Handle the message.
     */
    public function handle()
    {
        return $this->dispatcher()->run(new AddPrizeCommand($this->attributes));
    }
}
