<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Larandomizer - @yield('title')</title>
    <meta name="description" content="Larandomizer is a websocket server application written with Laravel and React PHP to give away prizes at meetups and conferences and to teach async in PHP">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="apple-touch-icon" href="apple-touch-icon.png"> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
  </head>
  <body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div id="app" v-cloak>
      @include('partials.nav')
      @yield('content')
    </div>
    @include('partials.footer')
    <script>
      window.Laravel = {};
      window.Laravel.csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
