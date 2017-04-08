<?php

namespace App\Server\Contracts;

interface Timer
{
    /**
     * Start the timer so that it may be actively executed
     * within the event loop.
     *
     * @return self
     */
    public function start();

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
    public function started($state = null);

    /**
     * Pause the timer so that it is no longer actively
     * executed within the event loop.
     *
     * @return self
     */
    public function pause();

    /**
     * Resume the timer so that it may be actively
     * executed within the event loop.
     *
     * @return self
     */
    public function resume();

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
    public function paused($state = null);

    /**
     * Stop the timer so that it is no longer executed
     * within the event loop.
     *
     * @return self
     */
    public function stop();

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
    public function counter($count = null);

    /**
     * Get or set the interval at which the periodic timer will be
     * executed within the event loop.
     *
     * @example interval() ==> int
     *          interval($milliseconds) ==> self
     *
     * @param int|float $milliseconds
     *
     * @return int|float|self
     */
    public function interval($milliseconds = null);

    /**
     * Get or set the timeout at which the periodic timer will be
     * stopped within the event loop.
     *
     * @example timeout() ==> int
     *          timeout($milliseconds) ==> self
     *
     * @param int|float $milliseconds
     *
     * @return int|float|self
     */
    public function timeout($milliseconds = null);

    /**
     * Set the timeout such that the timer only runs once.
     *
     * @return self
     */
    public function once();

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
    public function command($command = null);

    /**
     * Get or set the timer dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);

    /**
     * Run the command when the timer interval calls for it.
     */
    public function run();
}
