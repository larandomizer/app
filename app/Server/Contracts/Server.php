<?php

namespace App\Server\Contracts;

use Illuminate\Contracts\Queue\Queue;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\LoopInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface Server
{
    /**
     * Make a new instance of the server.
     *
     * @return self
     */
    public static function make();

    /**
     * Get a new or the existing instance of the server.
     *
     * @return self
     */
    public static function instance();

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
    public function bind($address, $port = null);

    /**
     * Start the server by running the event loop.
     */
    public function start();

    /**
     * Stop the server by stopping the event loop.
     */
    public function stop();

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
    public function config($key = null, $value = null);

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
    public function address($ip4 = null);

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
    public function port($number = null);

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
    public function bindings($address = null, $port = null);

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
    public function uses($service);

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
    public function connector(Queue $instance = null);

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
    public function queue($name = null);

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
    public function logger(OutputInterface $interface = null);

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
    public function manager(Manager $instance = null);

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
    public function broker(Broker $instance = null);

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
    public function websocket(WsServer $instance = null);

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
    public function http(HttpServer $instance = null);

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
    public function socket(IoServer $instance = null);

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
    public function loop(LoopInterface $instance = null);
}
