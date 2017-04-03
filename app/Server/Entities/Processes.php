<?php

namespace App\Server\Entities;

use App\Server\Contracts\Process;
use Illuminate\Support\Collection;

class Processes extends Collection
{
    /**
     * Add a command to the collection.
     *
     * @param App\Server\Contracts\Process $command
     *
     * @return self
     */
    public function add(Process $command)
    {
        $this->push($command);

        return $this;
    }

    /**
     * Remove a command from the collection.
     *
     * @param App\Server\Contracts\Process $command
     *
     * @return self
     */
    public function remove(Process $command)
    {
        $index = array_search($this->items, $command, $strict = true);
        if ($index === false) {
            $this->offsetUnset($index);
        }

        return $this;
    }

    /**
     * Get running processes.
     *
     * @return self
     */
    public function running()
    {
        return $this->filter(function($process){
            return $process->status() === true;
        });
    }

    /**
     * Get exited processes.
     *
     * @return self
     */
    public function exited()
    {
        return $this->filter(function($process){
            return $process->status() !== true;
        });
    }
}
