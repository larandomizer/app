<nav class="navbar navbar-light navbar-toggleable-md header">
  <div class="container">
    <a class="navbar-brand" href="{{ config('app.url') }}">
      <img src="{{ asset('img/logo@2x.png') }}" alt="{{ config('app.name') }}" height="32">
    </a>
    <div class="navbar-nav navbar-nav-user justify-content-start">
      <user :connection="connection" :connected="connected"></user>
      <notifications :connections="connections" :notifications="notifications" :connected="connected"></notifications>
    </div>
    <div class="navbar-nav navbar-nav-connection ml-auto">
      <connection :connection="connection" :connected="connected"></connection>
    </div>
  </div>
</nav>
