<?php

namespace App\Server\Commands;

use App\Server\Command;
use App\Server\Contracts\ClientCommand;
use App\Server\Traits\NoProtection;

class Authenticate extends Command implements ClientCommand
{
    use NoProtection;

    /**
     * Save the command arguments for later when the command is handled.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->password = array_get($arguments, 'password');
    }

    /**
     * Handle the command.
     */
    public function handle()
    {
        if( $this->listener()->password() !== $this->password ) {

           return $this->listener()
                ->send(new PromptForAuthentication($this), $this->client());
        }

        $this->client()->admin(true);

        return $this->listener()
            ->send(new Authenticated($this->client()), $this->client());
    }
}
