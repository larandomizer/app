<?php

namespace App\Server\Entities;

use App\Server\Contracts\Process;
use Illuminate\Support\Collection;

class Processes extends Collection
{
    /**
     * Add a process to the collection.
     *
     * @param App\Server\Contracts\Process $process
     *
     * @return self
     */
    public function add(Process $process)
    {
        $this->push($process);

        return $this;
    }

    /**
     * Remove a process from the collection.
     *
     * @param App\Server\Contracts\Process $process
     *
     * @return self
     */
    public function remove(Process $process)
    {
        $index = array_search($process, $this->items, $strict = true);
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
        return $this->filter(function ($process) {
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
        return $this->filter(function ($process) {
            return $process->status() !== true;
        });
    }
}
