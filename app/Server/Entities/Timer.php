<?php

namespace App\Server\Entities;

use App\Server\Contracts\Manager;
use App\Server\Contracts\Timer as TimerInterface;
use App\Server\Traits\FluentProperties;
use InvalidArgumentException;

abstract class Timer implements TimerInterface
{
    use FluentProperties;

    protected $command;
    protected $counter = 0;
    protected $dispatcher;
    protected $interval;
    protected $paused = false;
    protected $started = false;
    protected $timeout;
    protected $timer;

    /**
     * Start the timer so that it may be actively executed
     * within the event loop.
     *
     * @return self
     */
    public function start()
    {
        $this->started(true)
            ->paused(false);

        $this->timer = $this->dispatcher()->loop()
            ->addPeriodicTimer($this->interval(), function () {
                if ($this->started() && ! $this->paused()) {
                    $this->run();
                }
            });

        return $this;
    }

    /**
     * Get or set the started state of the timer.
     *
     * @example started() ==> bool
     *          started($state) ==> self
     *
     * @param bool $state
     *
     * @return bool|self
     */
    public function started($state = null)
    {
        return $this->property(__FUNCTION__, $state);
    }

    /**
     * Pause the timer so that it is no longer actively
     * executed within the event loop.
     *
     * @return self
     */
    public function pause()
    {
        $this->paused(true);

        return $this;
    }

    /**
     * Resume the timer so that it may be actively
     * executed within the event loop.
     *
     * @return self
     */
    public function resume()
    {
        $this->paused(false);

        return $this;
    }

    /**
     * Get or set the paused state of the timer.
     *
     * @example paused() ==> bool
     *          paused($state) ==> self
     *
     * @param bool $state
     *
     * @return bool|self
     */
    public function paused($state = null)
    {
        return $this->property(__FUNCTION__, $state);
    }

    /**
     * Stop the timer so that it is no longer executed
     * within the event loop.
     *
     * @return self
     */
    public function stop()
    {
        $this->started(false);
        $this->paused(false);

        $this->dispatcher()->loop()
            ->cancelTimer($this->timer);
        $this->timer = null;

        return $this;
    }

    /**
     * Get or set the number of event loop executed for the timer.
     *
     * @example counter() ==> int
     *          counter($count) ==> self
     *
     * @param int $count
     *
     * @return int|self
     */
    public function counter($count = null)
    {
        return $this->property(__FUNCTION__, $count);
    }

    /**
     * Get or set the interval at which the periodic timer will be
     * executed within the event loop.
     *
     * @example interval() ==> int
     *          interval($seconds) ==> self
     *
     * @param int|float $seconds
     *
     * @return int|float|self
     */
    public function interval($seconds = null)
    {
        return $this->property(__FUNCTION__, $seconds);
    }

    /**
     * Get or set the timeout at which the periodic timer will be
     * stopped within the event loop.
     *
     * @example timeout() ==> int
     *          timeout($seconds) ==> self
     *
     * @param int|float $seconds
     *
     * @return int|float|self
     */
    public function timeout($seconds = null)
    {
        return $this->property(__FUNCTION__, $seconds);
    }

    /**
     * Set the timeout such that the timer only runs once.
     *
     * @return self
     */
    public function once()
    {
        $this->timeout($this->interval());

        return $this;
    }

    /**
     * Get or set the timer dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the command that this timer will run.
     *
     * @example command() ==> \App\Server\Contracts\Command
     *          command('Command') ==> self
     *          command(new Command) ==> self
     *
     * @param string|\App\Server\Contracts\Command $command
     *
     * @return \App\Server\Contracts\Command|self
     */
    public function command($command = null)
    {
        if ( ! is_null($command)) {
            if (is_string($command)) {
                $command = app($command);
            }

            if ( ! $command instanceof Command) {
                throw new InvalidArgumentException(get_class($command).' must be an instance of '.Command::class.'.');
            }
        }

        return $this->property(__FUNCTION__, $command);
    }

    /**
     * Run the command when the timer interval calls for it.
     */
    public function run()
    {
        $this->command()
            ->dispatcher($this->dispatcher())
            ->run();
    }
}
