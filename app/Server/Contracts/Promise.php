<?php

namespace App\Server\Contracts;

interface Promise
{
    /**
     * Make a promise.
     *
     * @return \App\Server\Contracts\Promise
     */
    public static function make();

    /**
     * Get the underlying promise.
     *
     * @return \React\Promise\Promise
     */
    public function instance();

    /**
     * Get or set the command dispatcher.
     *
     * @param \App\Server\Contracts\Manager $instance for the server
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function dispatcher(Manager $instance = null);

    /**
     * Called when the promise is canceled.
     * Useful for resource cleanup.
     *
     * @return mixed
     */
    public function canceled();

    /**
     * Reject the underlying promise with an error.
     *
     * @param mixed $error
     */
    public function reject($error);

    /**
     * Called when the promise is rejected.
     *
     * @param mixed $error
     *
     * @return mixed
     */
    public function rejected($error = null);

    /**
     * Resolve the underlying promise with a value.
     *
     * @param mixed $result
     */
    public function resolve($result);

    /**
     * Called when the promise is resolved.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function resolved($result = null);

    /**
     * Chain another promise onto this promise.
     *
     * @param \App\Server\Contracts\Promise|string $promise
     *
     * @throws \InvalidArgumentException if promise argument is not a Promise instance.
     *
     * @return \App\Server\Contracts\Promise
     */
    public function then($promise);
}
