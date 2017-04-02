<?php

namespace App\Server;

use App\Server\Contracts\Broker as BrokerInterface;
use App\Server\Contracts\Manager as ManagerInterface;
use App\Server\Contracts\Server as ServerInterface;
use App\Server\Traits\FluentProperties;
use Illuminate\Contracts\Queue\Queue;
use Illuminate\Support\Facades\Queue as QueueManager;
use InvalidArgumentException;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Server implements ServerInterface
{
    use FluentProperties;

    protected $address = '0.0.0.0';
    protected $broker;
    protected $config = [];
    protected $connector;
    protected $http;
    protected $manager;
    protected $output;
    protected $port = 8080;
    protected $queue = 'default';
    protected $socket;
    protected $websocket;
    protected static $instance;

    /**
     * Make a new instance of the server.
     *
     * @return self
     */
    public static function make()
    {
        $server = app(static::class)
            ->uses(config('server'))
            ->uses(new Manager())
            ->uses(new Broker());

        self::$instance = $server
            ->uses(new WsServer($server->broker()))
            ->uses(new HttpServer($server->websocket()))
            ->uses(IoServer::factory($server->http(), $server->port(), $server->address()));

        return $server;
    }

    /**
     * Get a new or the existing instance of the server.
     *
     * @return self
     */
    public static function instance()
    {
        if ( ! self::$instance instanceof self) {
            self::make();
        }

        return self::$instance;
    }

    /**
     * Set the bindings for the server.
     *
     * @example bind('0.0.0.0')
     *          bind('0.0.0.0', 8080)
     *          bind(['0.0.0.0', 8080])
     *
     * @param string|array $address to bind to
     * @param int          $port    to listen on
     *
     * @return self
     */
    public function bind($address, $port = null)
    {
        if (is_array($address)) {
            list($address, $port) = $address;
        }

        $this->address($address);
        $this->port($port);

        return $this;
    }

    /**
     * Start the server by running the event loop.
     */
    public function start()
    {
        $this->manager()->start();
    }

    /**
     * Stop the server by stopping the event loop.
     */
    public function stop()
    {
        $this->manager()->stop();
    }

    /**
     * Get or set the config settings.
     *
     * @example config() ==> array
     *          config(array $settings) ==> self
     *          config('key') ==> mixed
     *          config('key', $value) ==> self
     *
     * @param array|string $key   to set or get
     * @param mixed        $value to set under the key
     *
     * @return mixed|self
     */
    public function config($key = null, $value = null)
    {
        if (is_array($key)) {
            return $this->property(__FUNCTION__, $key);
        }

        if (is_string($key) && is_null($value)) {
            return array_get($this->property(__FUNCTION__), $key);
        }

        if (is_string($key) && ! is_null($value)) {
            $config = $this->config();
            array_set($config, $key, $value);
            $this->config($config);

            return $this;
        }

        return $this->property(__FUNCTION__);
    }

    /**
     * Get or set the address the server binds to.
     *
     * @example address() ==> '0.0.0.0'
     *          address('0.0.0.0') ==> self
     *
     * @param string $ip4 address
     *
     * @return string|self
     */
    public function address($ip4 = null)
    {
        if ( ! is_null($ip4)) {
            $this->config(__FUNCTION__, $ip4);
        }

        return $this->property(__FUNCTION__, $ip4);
    }

    /**
     * Get or set the port the server listens on.
     *
     * @example port() ==> 8080
     *          port(8080) ==> self
     *
     * @param int $number for port
     *
     * @return string|self
     */
    public function port($number = null)
    {
        if ( ! is_null($number)) {
            $this->config(__FUNCTION__, $number);
        }

        return $this->property(__FUNCTION__, $number);
    }

    /**
     * Get or set the bindings for the server.
     *
     * @example bindings() ==> ['0.0.0.0', 8080]
     *          bindings('0.0.0.0') ==> self
     *          bindings('0.0.0.0', 8080) ==> self
     *          bindings(['0.0.0.0', 8080]) ==> self
     *
     * @param string|array $address to bind to
     * @param int          $port    to listen on
     *
     * @return array|self
     */
    public function bindings($address = null, $port = null)
    {
        if ( ! is_null($address)) {
            $this->bind($address, $port);
        }

        if (empty(func_get_args())) {
            return [$this->address, $this->port];
        }

        return $this;
    }

    /**
     * Set an instance of a service that should be used by the server.
     *
     * @example uses(\Illuminate\Contracts\Queue\Queue $service, 'default') to set a connector and queue
     *          uses(\Symfony\Component\Console\Output\OutputInterface $service) to set the output logging interface
     *          uses(\App\Server\Contracts\Manager $manager) to set connection manager
     *          uses(\App\Server\Contracts\Broker $broker) to set message broker
     *          uses(\Ratchet\WebSocket\WsServer $server) to set WebSocket server
     *          uses(\Ratchet\Http\HttpServer $server) to set HTTP server
     *          uses(\Ratchet\Server\IoServer $socket) to set I/O socket
     *          uses(\React\EventLoop\LoopInterface $loop) to set event loop
     *          uses(array $config) to set the configuration settings
     *
     * @param mixed $service
     *
     * @throws \InvalidArgumentException if service is not supported
     *
     * @return self
     */
    public function uses($service)
    {
        if ($service instanceof Queue) {
            return call_user_func_array([$this, 'usesQueue'], func_get_args());
        }

        if ($service instanceof OutputInterface) {
            return $this->logger($service);
        }

        if ($service instanceof ManagerInterface) {
            return $this->manager($service);
        }

        if ($service instanceof BrokerInterface) {
            return $this->broker($service);
        }

        if ($service instanceof WsServer) {
            return $this->websocket($service);
        }

        if ($service instanceof HttpServer) {
            return $this->http($service);
        }

        if ($service instanceof IoServer) {
            return $this->socket($service);
        }

        if ($service instanceof LoopInterface) {
            return $this->loop($service);
        }

        if (is_array($service)) {
            return $this->config($service);
        }

        throw new InvalidArgumentException(get_class($service).' is not a supported service.');
    }

    /**
     * Set the queue the server processes.
     *
     * @example usesQueue() is equivalent to usesQueue('default', 'default')
     *          usesQueue($connection) to inject an existing connector
     *          usesQueue('beanstalkd') to use beanstalkd driver on default queue
     *          usesQueue('beanstalkd', 'server') to use beanstalkd driver on server queue
     *
     * @param string|\Illuminate\Contracts\Queue\Queue $connection
     * @param string                                   $name
     *
     * @return self
     */
    public function usesQueue($connection = null, $name = null)
    {
        if ( ! $connection instanceof Queue) {
            $connection = QueueManager::connection($connection);
        }

        $this->connector($connection);
        $this->queue($name);

        return $this;
    }

    /**
     * Get or set the queue connector the server uses.
     *
     * @example connector() ==> \Illuminate\Contracts\Queue\Queue
     *          connector($instance) ==> self
     *
     * @param \Illuminate\Contracts\Queue\Queue $instance
     *
     * @return \Illuminate\Contracts\Queue\Queue|self
     */
    public function connector(Queue $instance = null)
    {
        if ( ! is_null($instance)) {
            $this->config(__FUNCTION__, $instance);
        }

        $this->manager()->connector($instance);

        return $this;
    }

    /**
     * Get or set the queue the server processes.
     *
     * @example queue() ==> 'server'
     *          queue('server') ==> self
     *
     * @param string $name of queue
     *
     * @return string|self
     */
    public function queue($name = null)
    {
        if ( ! is_null($name)) {
            $this->config(__FUNCTION__, $name);
        }

        $this->manager()->queue($name);

        return $this;
    }

    /**
     * Get or set the logger interface the server pipes output to.
     *
     * @example logger() ==> \Symfony\Component\Console\Output\OutputInterface
     *          logger($interface) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function logger(OutputInterface $interface = null)
    {
        $this->broker()->logger($interface);

        return $this;
    }

    /**
     * Get or set the connection manager the server uses.
     *
     * @example manager() ==> \App\Server\Contracts\Manager
     *          manager($instance) ==> self
     *
     * @param \App\Server\Contracts\Manager $instance
     *
     * @return \App\Server\Contracts\Manager|self
     */
    public function manager(ManagerInterface $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the message broker the server uses.
     *
     * @example broker() ==> \App\Server\Contracts\Broker
     *          broker($instance) ==> self
     *
     * @param \App\Server\Contracts\Broker $instance
     *
     * @return \App\Server\Contracts\Broker|self
     */
    public function broker(BrokerInterface $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the WebSocket instance the server uses.
     *
     * @example websocket() ==> \Ratchet\WebSocket\WsServer
     *          websocket($instance) ==> self
     *
     * @param \Ratchet\WebSocket\WsServer $instance
     *
     * @return \Ratchet\WebSocket\WsServer|self
     */
    public function websocket(WsServer $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the HTTP instance the server uses.
     *
     * @example http() ==> \Ratchet\Http\HttpServer
     *          http($instance) ==> self
     *
     * @param \Ratchet\Http\HttpServer $instance
     *
     * @return \Ratchet\Http\HttpServer|self
     */
    public function http(HttpServer $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the I/O instance the server uses.
     *
     * @example socket() ==> \Ratchet\Server\IoServer
     *          socket($instance) ==> self
     *
     * @param \Ratchet\Server\IoServer $instance
     *
     * @return \Ratchet\Server\IoServer|self
     */
    public function socket(IoServer $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the event loop the server uses.
     *
     * @example loop() ==> \React\EventLoop\LoopInterface
     *          loop($instance) ==> self
     *
     * @param \React\EventLoop\LoopInterface $instance
     *
     * @return \React\EventLoop\LoopInterface|self
     */
    public function loop(LoopInterface $instance = null)
    {
        if ( ! is_null($instance)) {
            $this->socket()->loop = $instance;

            return $this;
        }

        return $this->socket()->loop;
    }

    /**
     * Map undefined methods to config() method calls.
     *
     * @param  password() ==> config('password') ==> mixed
     *         password($value) ==> config('password', $value) ==> self
     * @param string $method which maps to config key
     * @param array  $args   which become the config value
     *
     * @return mixed|self
     */
    public function __call($method, $args = [])
    {
        array_unshift($args, snake_case($method));

        return call_user_func_array([$this, 'config'], $args);
    }
}
