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

### Extra Dependencies

If you plan to use any AWS resources or async S3 filesystem interactions then you
will want to install the `league/flysystem-aws-s3-v3` dependency. If you plan to
use the APIs to interact with the Beanstalkd realtime queue bridge then you'll
also need to install the `pda/pheanstalk` dependency:

- `composer require league/flysystem-aws-s3-v3 ~1.0`
- `composer require pda/pheanstalk ~3.0`

## Usage Guide

This application depends on [`artisansdk/server`](http://github.com/artisansdk/server)
which is a service-based, Laravel PHP implementation of an async, realtime,
WebSocket server you also can use in your realtime dashboards and applications.
See [this package's readme](http://github.com/artisansdk/server) for more installation
and configuration notes including a usage guide.

> **TLDR:** Run `php artisan server:start` and the app connects to `wss://localhost:8080`.

### Configuration Settings

The configuration settings of the [`artisansdk/server`](http://github.com/artisansdk/server)
service have been published to `config/server.php` which is where you will be
able to customize some of the settings. Additionally the `.env.example` file
already has the environmental variables included for customization.

## Licensing

Copyright (c) 2017 [Artisans Collaborative](http://artisanscollaborative.com)

This package is released under the MIT license. Please see the LICENSE file
distributed with every copy of the code for commercial licensing terms.
