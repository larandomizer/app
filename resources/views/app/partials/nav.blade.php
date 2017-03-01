<nav class="navbar navbar-light navbar-toggleable-md header">
    <div class="container">
        <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        <div class="navbar-nav navbar-nav-user justify-content-start">
            <user :connection="connection" :connected="connected"></user>
            <notifications :notifications="notifications" :connected="connected"></notifications>
        </div>
        <div class="navbar-nav navbar-nav-connection ml-auto">
            <connection :connection="connection" :connected="connected"></connection>
        </div>
    </div>
</nav>
