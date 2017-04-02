<?php

namespace App\Server\Messages;

use App\Server\Contracts\ClientMessage;
use App\Server\Contracts\SelfHandling;
use App\Server\Entities\Message;
use App\Server\Server;
use App\Server\Traits\NoProtection;

class Authenticate extends Message implements ClientMessage, SelfHandling
{
    use NoProtection;

    /**
     * Save the message arguments for later when the message is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->password = array_get($arguments, 'password');
    }

    /**
     * Handle the message.
     */
    public function handle()
    {
        if (Server::instance()->password() !== $this->password) {
            return $this->dispatcher()
                ->send(new PromptForAuthentication($this), $this->client());
        }

        $this->client()->admin(true);

        return $this->dispatcher()
            ->send(new ConnectionAuthenticated($this->client()), $this->client());
    }
}
