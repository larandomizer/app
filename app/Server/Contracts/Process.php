<?php

namespace App\Server\Contracts;

use React\ChildProcess\Process as ReactProcess;
use React\EventLoop\LoopInterface;
use React\Stream\Stream;

interface Process
{
    /**
     * Make the command into a process.
     *
     * @param string $command to execute
     *
     * @return self
     */
    public static function make($command);

    /**
     * Get or set the underlying process instance.
     *
     * @example instance() ==> \React\ChildProcess\Process
     *          instance($process) ==> self
     *
     * @param \React\ChildProcess\Process $process
     *
     * @return \React\ChildProcess\Process|self
     */
    public function instance(ReactProcess $process = null);

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);

    /**
     * Start the command in the loop.
     *
     * @param \React\EventLoop\LoopInterface $loop
     *
     * @return self
     */
    public function start(LoopInterface $loop);

    /**
     * Boot any listeners for this process that must be registered after
     * the process is started and the input/output streams are setup.
     *
     * @return self
     */
    public function boot();

    /**
     * Get the running status of the process.
     *
     * @return int|bool
     */
    public function status();

    /**
     * Stop the process that is running and clean up streams.
     *
     * @return self
     */
    public function stop();

    /**
     * Get or set the process input stream.
     *
     * @example input() ==> \React\Stream\Stream
     *          input($stream) ==> self
     *
     * @param \React\Stream\Stream $stream
     *
     * @return \React\Stream\Stream|self
     */
    public function input(Stream $stream = null);

    /**
     * Get or set the process output stream.
     *
     * @example output() ==> \React\Stream\Stream
     *          output($stream) ==> self
     *
     * @param \React\Stream\Stream $stream
     *
     * @return \React\Stream\Stream|self
     */
    public function output(Stream $stream = null);

    /**
     * Get or set the process error stream.
     *
     * @example error() ==> \React\Stream\Stream
     *          error($stream) ==> self
     *
     * @param \React\Stream\Stream $stream
     *
     * @return \React\Stream\Stream|self
     */
    public function error(Stream $stream = null);
}
