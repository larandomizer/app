<?php

namespace App\Server;

use App\Server\Commands\AddQueueWorker;
use App\Server\Contracts\Broker as BrokerInterface;
use App\Server\Contracts\Command;
use App\Server\Contracts\Connection;
use App\Server\Contracts\Manager as ManagerInterface;
use App\Server\Contracts\Message;
use App\Server\Contracts\Topic;
use App\Server\Entities\Commands;
use App\Server\Entities\Connections;
use App\Server\Entities\Prizes;
use App\Server\Entities\Topics;
use App\Server\Messages\ConnectionEstablished;
use App\Server\Messages\CurrentUptime;
use App\Server\Messages\PromptForAuthentication;
use App\Server\Messages\UpdateConnections;
use App\Server\Messages\UpdatePrizes;
use App\Server\Messages\UpdateSubscriptions;
use App\Server\Messages\UpdateTopics;
use App\Server\Traits\FluentProperties;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Contracts\Queue\Queue;
use Ramsey\Uuid\Uuid;
use React\EventLoop\LoopInterface as Loop;

class Manager implements ManagerInterface
{
    use FluentProperties;

    protected $broker;
    protected $commands;
    protected $connections;
    protected $connector;
    protected $loop;
    protected $password;
    protected $prizes;
    protected $queue;
    protected $start;
    protected $topics;

    /**
     * Inject and setup the dependencies.
     *
     * @param \App\Server\Entities\Connections $connections
     * @param \App\Server\Entities\Prizes      $prizes
     */
    public function __construct(Connections $connections, Prizes $prizes = null)
    {
        $this->connections($connections);
        $this->prizes($prizes ?: new Prizes());
    }

    /**
     * Get or set the password the server accepts for admin commands.
     *
     * @example password() ==> 'opensesame'
     *          password('opensesame') ==> self
     *
     * @param string $password
     *
     * @return string|self
     */
    public function password($password = null)
    {
        return $this->property(__FUNCTION__, $password);
    }

    /**
     * Called when the server is started.
     *
     * @return self
     */
    public function start()
    {
        $this->start = Carbon::now();

        // Demonstration of a timer where the server keeps time
        $this->loop()->addPeriodicTimer(1, function () {
            $this->broadcast(new CurrentUptime($this->start), $this->connections());
        });

        // Restart server every hour
        $this->loop()->addPeriodicTimer(3600, function () {
            $this->stop();
        });

        // Register a queue worker to process queued messages every 100ms
        $this->run(new AddQueueWorker(['timing' => 1 / 10]));

        // Start the actual loop: starts blocking
        $this->loop()->run();

        return $this;
    }

    /**
     * Called when the server is stopped.
     *
     * @return self
     */
    public function stop()
    {
        $this->loop()->stop();

        return $this;
    }

    /**
     * Called when a new connection is opened.
     *
     * @param \App\Server\Contracts\Connection $connection being opened
     *
     * @return self
     */
    public function open(Connection $connection)
    {
        $this->connections()->put($connection->uuid(), $connection);

        $this->send(new ConnectionEstablished($connection), $connection)
            ->send(new UpdatePrizes($this->prizes()), $connection)
            ->broadcast(new UpdateConnections($this->connections()), $this->connections());

        return $this;
    }

    /**
     * Send message to one connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function send(Message $message, Connection $connection)
    {
        $message = $this->prepareMessageForBroker($message);

        $this->broker()->send($message, $connection);

        return $this;
    }

    /**
     * Send message to one connection and then close the connection.
     *
     * @param \App\Server\Contracts\Message    $message    to send
     * @param \App\Server\Contracts\Connection $connection to send to
     *
     * @return self
     */
    public function end(Message $message, Connection $connection)
    {
        $this->broker()->end($message, $connection);

        return $this;
    }

    /**
     * Broadcast message to multiple connections.
     *
     * @param \App\Server\Contracts\Message    $message
     * @param \App\Server\Entities\Connections $connections to send to
     *
     * @return self
     */
    public function broadcast(Message $message, Connections $connections)
    {
        $message = $this->prepareMessageForBroker($message);

        if ($message->topics()->count()) {
            $connections = $connections->topics($message->topics());
        }

        $this->broker()->broadcast($message, $connections);

        return $this;
    }

    /**
     * Called when a new message is received from an open connection.
     *
     * @param \App\Server\Contracts\Message    $message    payload received
     * @param \App\Server\Contracts\Connection $connection sending the message
     *
     * @return self
     */
    public function receive(Message $message, Connection $connection)
    {
        $message->dispatcher($this);

        if ( ! $message->client($connection)->authorize()) {
            return $this->send(new PromptForAuthentication($message), $connection);
        }

        $message->handle();

        return $this;
    }

    /**
     * Called when an open connection is closed.
     *
     * @param \App\Server\Contracts\Connection $connection to be closed
     *
     * @return self
     */
    public function close(Connection $connection)
    {
        $this->connections()->forget($connection->uuid());

        $this->broadcast(new UpdateConnections($this->connections()), $this->connections());

        return $this;
    }

    /**
     * Called when an error occurs on the connection.
     *
     * @param \App\Server\Contracts\Connection $connection that errored
     * @param \Exception                       $exception  caught
     *
     * @return self
     */
    public function error(Connection $connection, Exception $exception)
    {
        $connection->close();

        $this->close($connection);

        return $this;
    }

    /**
     * Get or set the connections on the server.
     *
     * @example connections() ==> \App\Server\Entities\Connections
     *          connections($connections) ==> self
     *
     * @param \App\Server\Entities\Connections $connections
     *
     * @return \App\Server\Entities\Connections|self
     */
    public function connections(Connections $connections = null)
    {
        return $this->property(__FUNCTION__, $connections);
    }

    /**
     * Get or set the topics available for subscribing.
     *
     * @example topics() ==> \App\Server\Entities\Topics
     *          topics($topics) ==> self
     *
     * @param \App\Server\Entities\Topics $topics
     *
     * @return \App\Server\Entities\Topics|self
     */
    public function topics(Topics $topics = null)
    {
        return $this->property(__FUNCTION__, $topics);
    }

    /**
     * Register a new topic in the collection of topics.
     *
     * @param \App\Server\Contracts\Topic $topic to register
     *
     * @return self
     */
    public function register(Topic $topic)
    {
        $this->topics()->put($topic->uuid(), $topic);

        $this->broadcast(new UpdateTopics($this->topics()), $this->connections());

        return $this;
    }

    /**
     * Unregister an existing topic from the collection of topics.
     *
     * @param \App\Server\Contracts\Topic $topic to unregister
     *
     * @return self
     */
    public function unregister(Topic $topic)
    {
        $this->topics()->forget($topic->uuid());
        $topic->subscriptions()->each(function ($connection) {
            $connection->unsubscribe();
        });
        $topic->subscriptions(new Connections());

        $this->broadcast(new UpdateTopics($this->topics()), $this->connections());

        return $this;
    }

    /**
     * Subscribe a connection to the topic.
     *
     * @param \App\Server\Contracts\Topic      $topic      to subscribe to
     * @param \App\Server\Contracts\Connection $connection to subscribe to topic
     *
     * @return self
     */
    public function subscribe(Topic $topic, Connection $connection)
    {
        if ($this->topics()->uuid($topic)) {
            $connection->subscribe($topic);
            $topic->subscribe($connection);
            $this->send(new UpdateSubscriptions($connection->subscriptions()), $connection);
        }

        return $this;
    }

    /**
     * Unsubscribe a connection from the topic.
     *
     * @param \App\Server\Contracts\Topic      $topic      to unsubscribe from
     * @param \App\Server\Contracts\Connection $connection to unsubscribe from topic
     *
     * @return self
     */
    public function unsubscribe(Topic $topic, Connection $connection)
    {
        if ($connection->subscriptions()->uuid($topic)) {
            $connection->unsubscribe($topic);
            $topic->unsubscribe($connection);
            $this->send(new UpdateSubscriptions($connection->subscriptions()), $connection);
        }

        return $this;
    }

    /**
     * Get or set the event loop the server runs on.
     *
     * @example loop() ==> \React\EventLoop\LoopInterface
     *          loop($instance) ==> self
     *
     * @param \React\EventLoop\LoopInterface $instance
     *
     * @return \React\EventLoop\LoopInterface|self
     */
    public function loop(Loop $instance = null)
    {
        return $this->property(__FUNCTION__, $instance);
    }

    /**
     * Get or set the broker that communicates with the server.
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
        return $this->property(__FUNCTION__, $instance);
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
        return $this->property(__FUNCTION__, $name);
    }

    /**
     * Process a job that has been popped off the queue.
     *
     * @param \Illuminate\Contracts\Queue\Job $job to be processed
     *
     * @return self
     */
    public function work(Job $job)
    {
        $payload = $job->getRawBody();
        $message = json_decode($payload, true);
        $arguments = array_get($message, 'data', []);

        $command = array_get($message, 'job');
        if ( ! is_null($command)) {
            if ( ! class_exists($command)) {
                throw new Exception("Command $command not found.");
            }
            $this->run(new $command($arguments));
        }

        $job->delete();

        return $this;
    }

    /**
     * Get or set the commands available to be ran.
     *
     * @example commands() ==> \App\Server\Entities\Commands
     *          commands($commands) ==> self
     *
     * @param \App\Server\Entities\Commands $commands
     *
     * @return \App\Server\Entities\Commands|self
     */
    public function commands(Commands $commands = null)
    {
        return $this->property(__FUNCTION__, $commands);
    }

    /**
     * Run a command immediately within this tick of the event loop.
     *
     * @param \App\Server\Contracts\Command $command to run
     *
     * @return self
     */
    public function run(Command $command)
    {
        $command->dispatcher($this)->run();

        return $this;
    }

    /**
     * Run a command in the next tick of the event loop.
     *
     * @param \App\Server\Contracts\Command $command to run
     *
     * @return self
     */
    public function next(Command $command)
    {
        $this->commands()->push($command);

        // @todo handle executing this on the next tick

        return $this;
    }

    /**
     * Abort a command before it has a chance to run.
     *
     * @param \App\Server\Contracts\Command $command to abort
     *
     * @return self
     */
    public function abort(Command $command)
    {
        $this->commands()->pull($command);

        return $this;
    }

    /**
     * Get or set the prizes available on the server.
     *
     * @example prizes() ==> \App\Server\Entities\Prizes
     *          prizes($prizes) ==> self
     *
     * @param \App\Server\Entities\Prizes $prizes
     *
     * @return \App\Server\Entities\Prizes|self
     */
    public function prizes(Prizes $prizes = null)
    {
        return $this->property(__FUNCTION__, $prizes);
    }

    /**
     * Prepare the message to be sent out over the broker.
     *
     * @param \App\Server\Contracts\Message $message
     *
     * @return \App\Server\Contracts\Message
     */
    protected function prepareMessageForBroker(Message $message)
    {
        return $message->id(Uuid::uuid4()->toString())
            ->name(class_basename($message))
            ->timestamp(microtime(true));
    }
}
