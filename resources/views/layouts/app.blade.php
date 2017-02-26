<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
  <head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('pageTitle')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="stylesheet" href="{{ config('app.url') }}/css/app.css">
    <script src="{{ config('app.url') }}/js/modernizr-2.8.3.min.js"></script>
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
    <script src="{{ config('app.url') }}/js/app.js"></script>
  </body>
</html>
