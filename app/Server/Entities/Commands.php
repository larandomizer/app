<?php

namespace App\Server\Entities;

use App\Server\Contracts\Command;
use Illuminate\Support\Collection;

class Commands extends Collection
{
    /**
     * Add a command to the collection.
     *
     * @param App\Server\Contracts\Command $command
     *
     * @return self
     */
    public function add(Command $command)
    {
        $this->push($command);

        return $this;
    }

    /**
     * Remove a command from the collection.
     *
     * @param App\Server\Contracts\Command $command
     *
     * @return self
     */
    public function remove(Command $command)
    {
        $index = array_search($this->items, $command, $strict = true);
        if ($index === false) {
            $this->offsetUnset($index);
        }

        return $this;
    }
}
