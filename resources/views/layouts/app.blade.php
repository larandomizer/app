<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('pageTitle')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  </head>
  <body>
    <!--[if lte IE 9]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div id="app">
      @include('app.partials.nav')
      @yield('content')
    </div>
    @include('app.partials.footer')
    <script>
      window.Laravel = {};
      window.Laravel.csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
  </body>
</html>
