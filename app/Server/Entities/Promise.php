<?php

namespace App\Server\Entities;

use App\Server\Contracts\Manager;
use App\Server\Contracts\Promise as PromiseInterface;
use App\Server\Server;
use App\Server\Traits\FluentProperties;
use Exception;
use InvalidArgumentException;
use React\Promise\Deferred;

abstract class Promise implements PromiseInterface
{
    use FluentProperties;

    protected $canceler;
    protected $deferred;
    protected $dispatcher;
    protected $instance;
    protected $resolver;
    protected $promises;

    /**
     * Setup the deferred promise.
     */
    public function __construct()
    {
        $this->canceler = function ($resolve, $reject) {
            $reject($this->canceled());
        };

        $deferred = new Deferred($this->canceler);

        $this->resolver = function ($error, $result) use ($deferred) {
            if ($error) {
                $deferred->reject($this->rejected($error));
            }

            $deferred->resolve($this->resolved($result));
        };

        $this->instance = $deferred->promise();
    }

    /**
     * Make a promise.
     *
     * @return \App\Server\Contracts\Promise
     */
    public static function make()
    {
        return with(new static())
            ->dispatcher(Server::instance()->manager());
    }

    /**
     * Get the underlying promise.
     *
     * @return \React\Promise\Promise
     */
    public function instance()
    {
        return $this->instance;
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
     * Called when the promise is canceled.
     * Useful for resource cleanup.
     *
     * @return mixed
     */
    public function canceled()
    {
    }

    /**
     * Reject the underlying promise with an error.
     *
     * @param mixed $error
     */
    public function reject($error)
    {
        call_user_func_array($this->resolver, [$error, null]);
    }

    /**
     * Called when the promise is rejected.
     *
     * @param mixed $error
     *
     * @return mixed
     */
    public function rejected($error = null)
    {
        return new Exception($error);
    }

    /**
     * Resolve the underlying promise with a value.
     *
     * @param mixed $result
     */
    public function resolve($result)
    {
        call_user_func_array($this->resolver, [null, $result]);
    }

    /**
     * Called when the promise is resolved.
     *
     * @param mixed $result
     *
     * @return mixed
     */
    public function resolved($result = null)
    {
        return $result;
    }

    /**
     * Chain another promise onto this promise.
     *
     * @param \App\Server\Contracts\Promise|string $promise
     *
     * @throws \InvalidArgumentException if promise argument is not a Promise instance.
     *
     * @return \App\Server\Contracts\Promise
     */
    public function then($promise)
    {
        if (is_string($promise)) {
            $promise = app($promise);
        }

        if ( ! $promise instanceof PromiseInterface) {
            throw new InvalidArgumentException(get_class($promise).' must be an instance of '.PromiseInterface::class.'.');
        }

        $promise->dispatcher($this->dispatcher());

        $this->promises = $this->promises ?: [$this];
        $last = end($this->promises);
        $last->instance()->then(
            function ($result) use ($promise) {
                return $promise->resolve($result);
            },
            function ($error) use ($promise) {
                return $promise->reject($error);
            }
        );

        array_push($this->promises, $promise);

        return $this;
    }
}
