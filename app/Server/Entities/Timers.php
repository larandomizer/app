<?php

namespace App\Server\Entities;

use App\Server\Contracts\ShouldAutoStart;
use App\Server\Contracts\Timer;
use Illuminate\Support\Collection;

class Timers extends Collection
{
    /**
     * Add a timer to the collection.
     *
     * @param App\Server\Contracts\Timer $timer
     *
     * @return self
     */
    public function add(Timer $timer)
    {
        $this->push($timer);

        if ($timer instanceof ShouldAutoStart) {
            $timer->start();
        }

        return $this;
    }

    /**
     * Remove a timer from the collection.
     *
     * @param App\Server\Contracts\Timer $timer
     *
     * @return self
     */
    public function remove(Timer $timer)
    {
        $timer->stop();

        $index = array_search($this->items, $timer, $strict = true);
        if ($index === false) {
            $this->offsetUnset($index);
        }

        return $this;
    }

    /**
     * Filter timers to those that are active.
     *
     * @param bool $include active timers.
     *
     * @return self
     */
    public function active($include = true)
    {
        return $this->where(function ($timer) use ($include) {
            return $timer->started() === $include
                && $timer->paused() !== $include;
        });
    }

    /**
     * Filter timers to those that are paused.
     *
     * @param bool $include paused timers.
     *
     * @return self
     */
    public function paused($include = true)
    {
        return $this->where(function ($timer) use ($include) {
            return $timer->paused() === $include;
        });
    }
}
