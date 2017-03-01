<?php

namespace App\Server\Commands;

use App\Server\Entities\Command;
use App\Server\Entities\Topic;

class RegisterTopic extends Command
{
    /**
     * Save the command arguments for later when the command is run.
     *
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->name = array_get($arguments, 'name');
    }

    /**
     * Run the command.
     */
    public function run()
    {
        $this->dispatcher()->register(new Topic($this->name));
    }
}
