# Larandomizer

Larandomizer is a websocket server application written with Laravel and React PHP
to give away prizes at meetups and conferences and to teach async in PHP. This
repository is the Laravel application that you install and deploy. It includes
all the necessary ReactPHP components as dependencies.

## Installation

The application installs like any Laravel application. The following shows one
possible way to install the application. While Composer is required, Yarn is
optional and you could just as easily use NPM instead.

```
mkdir larandomizer
cd larandomizer
git clone https://github.com/larandomizer/app ./
composer install
yarn install
npm run production
cp .env.example .env
php artisan key:generate
```

You will still want to edit the `.env` file to customize environment settings.
No database is used as all data is stored in memory on the server.

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
it if ever it crashes.
