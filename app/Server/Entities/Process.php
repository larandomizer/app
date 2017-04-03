<?php

namespace App\Server\Entities;

use App\Server\Contracts\Manager;
use App\Server\Contracts\Process as ProcessInterface;
use App\Server\Traits\FluentProperties;
use Exception;
use React\ChildProcess\Process as ReactProcess;
use React\EventLoop\LoopInterface;
use React\Stream\Stream;

abstract class Process implements ProcessInterface
{
    use FluentProperties;

    protected $instance;
    protected $dispatcher;

    /**
     * Setup the process.
     *
     * @param \React\ChildProcess\Process $process
     */
    public function __construct(ReactProcess $process)
    {
        $this->instance($process);
    }

    /**
     * Make the command into a process.
     *
     * @param string $command to execute
     *
     * @return self
     */
    public static function make($command)
    {
        return new static(new ReactProcess($command));
    }

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
    public function instance(ReactProcess $process = null)
    {
        return $this->property(__FUNCTION__, $process);
    }

    /**
     * Get or set the command dispatcher.
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
     * Start the command in the loop.
     *
     * @param \React\EventLoop\LoopInterface $loop
     *
     * @return self
     */
    public function start(LoopInterface $loop)
    {
        $this->instance()->start($loop);

        $this->boot();

        return $this;
    }

    /**
     * Boot any listeners for this process that must be registered after
     * the process is started and the input/output streams are setup.
     *
     * @return self
     */
    public function boot()
    {
        return $this;
    }

    /**
     * Get the running status of the process.
     *
     * @return int|bool
     */
    public function status()
    {
        return $this->isRunning() ? true : $this->getExitCode();
    }

    /**
     * Stop the process that is running and clean up streams.
     *
     * @return self
     */
    public function stop()
    {
        $this->instance()->terminate();

        return $this;
    }

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
    public function input(Stream $stream = null)
    {
        if ( ! is_null($stream)) {
            $this->instance()->stdin = $stream;

            return $this;
        }

        return $this->instance()->stdin;
    }

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
    public function output(Stream $stream = null)
    {
        if ( ! is_null($stream)) {
            $this->instance()->stdout = $stream;

            return $this;
        }

        return $this->instance()->stdout;
    }

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
    public function error(Stream $stream = null)
    {
        if ( ! is_null($stream)) {
            $this->instance()->stderr = $stream;

            return $this;
        }

        return $this->instance()->stderr;
    }

    /**
     * Log the message to the broker log.
     *
     * @param string $message
     */
    protected function log($message)
    {
        $this->dispatcher()->broker()->log(rtrim($message, PHP_EOL));
    }

    /**
     * Forward method calls to underlying instance.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @throws Exception if method is not defined on instance.
     *
     * @return mixed
     */
    public function __call($method, $arguments = [])
    {
        if (method_exists($this->instance(), $method)) {
            return call_user_func_array([$this->instance(), $method], $arguments);
        }

        throw new Exception($method.' is not a defined method of '.__CLASS__.'.');
    }
}
