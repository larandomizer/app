# Larandomizer

Larandomizer is a websocket server application written with Laravel and React PHP
to give away prizes at meetups and conferences and to teach async in PHP. This
repository is the Laravel application that you install and deploy. It includes
all the necessary ReactPHP components as dependencies.

From the talk, _[It's All PHP I Promise](https://drive.google.com/open?id=0B0huSIRObL68Qjdya1h0QUxzdDA)_

## Installation

The application installs like any Laravel application. The following shows one
possible way to install the application. While Composer is required, Yarn is
optional and you could just as easily use NPM instead.

```
git clone https://github.com/larandomizer/app ./
composer install
yarn install
npm run production
cp .env.example .env
php artisan key:generate
```

### Configure the Environment

You will still want to edit the `.env` file to customize environment settings.
Note that no database is used as all data is stored in memory on the server.
Restarting the server will cause all data to be lost. Below are available options
for server customization:

- `SERVER_ADDRESS` (`127.0.0.1`): sets the address the server should bind to (`0.0.0.0` would be for allowing all external connections)
- `SERVER_PORT` (`8080`): sets the port the server will listen on for websocket connections
- `SERVER_MAX_CONNECTIONS` (`100`): the server rejects new connections after this limit (set to `0` to allow unlimited)
- `SERVER_QUEUE` (`default`): the name of the queue that realtime messages will be sent to
- `SERVER_QUEUE_DRIVER` (`beanstalkd`): the driver to use for the realtime message queue
- `SERVER_KEY` (`password`): the admin password to authenticate connections against admin protected connections

There is a basic auth scheme in place which allows the server to `PromptForAuthentication`
against a connection and then remember that the connection is authenticated. This
simplifies further message processing and relies on any `ClientMessage` that must
be authenticated to implement the `authorize()` method. There are three basic
traits that can be used on any message to achieve a couple of common strategies:

- `App\Server\Traits\NoProtection`: always returns true so allows any client to send the message
- `App\Server\Traits\ClientProtection`: allows admin connections and a specific related connection to be authorized
- `App\Server\Traits\AdminProtection`: allows only admins to be authorized to send the message

### Nginx Websocket Proxy Configuration

Nginx makes the perfect lightweight frontend server for the Laravel backend
application. Additionally it can be used to proxy websockets connecting on port
`80` to the `8080` default server socket. Doing so helps get around some firewall
settings. The following should be placed just before your default `location`
directive for the Laravel application itself (e.g.: Forge's default). Using these
settings you can host websockets securely with the `wss://` protocol allowing
Nginx to handle the SSL connection and your websocket server handling basic HTTP.

```
location /socket/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header Host $host;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_read_timeout 5m;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
}
```

A quick note on the settings used:

- `location /socket/` directs all traffic going to `/socket/` to the proxy
- `proxy_pass` passes the traffic to the localhost webserver on port `8080`
- `proxy_read_timeout` customizes the connection drop to hang up idle connections
- `proxy_http_version` is the version of the websocket protocol in HTTP
- `X-Real-IP` header gives your websocket server the real IP of the client connection
- `Upgrade` and `Connection` headers instruct the browser to upgrade to websocket connection

### Starting the Server

The websocket server can be ran as an console command using `php artisan server:start`
and if you pass `--help` to the command you can see additional options. You can
stop the running server by killing the process with `CMD + C` (or `CTRL + C`).

In production you would want to have Supervisor monitor the server and restart
it if ever it crashes. The demo application has a "Restart Server" command which
actually just stops the server and expects Supervisor to start it again automatically.
If you are using Laravel Forge this is pretty easy to do by adding a New Deamon
on the server with a configuration of:

- Command: `/usr/bin/php /home/forge/default/artisan server:start`
- User: `forge`

The resulting Supervisor config might be:

```
[program:server]
command=/usr/bin/php /home/forge/larandomizer.com/artisan server:start
autostart=true
autorestart=true
user=forge
redirect_stderr=true
startsecs=1
stdout_logfile=/home/forge/.forge/server.log
```

Forge does not add the `startsecs` by default but in practice this may be needed
to give the server ample time to start without hard exiting and forcing Supervisor
to give up on starting the process.

### Pushing Messages to the Realtime Queue

By default the `App\Server\Manager@start()` method adds a queue worker to the
async event loop so that "offline" messages can be sent to the "realtime" connected
websocket clients. You can use any async driver (basically don't use `sync` as
the queue driver) but if you are using Laravel Forge it is pretty easy to use
`beanstalkd` driver. Set `SERVER_QUEUE_DRIVER` and `SERVER_QUEUE` in your `.env`
to configure the driver and queue name for your realtime messages.

To send messages from your "offline" code (e.g.: controllers, repositories, etc.)
to your "realtime" code you can `use App\Server\Traits\WebsocketQueue` trait in
your caller class and then call `$this->queue(new Command)` to push server
commands into the event loop of the websocket server. Commands should run nearly
instantly though there can be some lag depending on remaining commands within the
event loop. You can tweak the timing of the worker in `App\Server\Manager@start()`
method's configuration of the worker.

## Licensing

Copyright (c) 2017 [Artisans Collaborative](http://artisanscollaborative.com)

This package is released under the MIT license. Please see the LICENSE file
distributed with every copy of the code for commercial licensing terms.
