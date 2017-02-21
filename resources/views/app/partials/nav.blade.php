<nav class="navbar navbar-light navbar-toggleable-md header">
    <div class="container">
        <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        <div class="navbar-nav justify-content-start">
            <user :user="currentUser" :connection="currentConnection"></user>
            <notifications :messages="messages" :status="currentConnection"></notifications>
        </div>
        <div class="navbar-nav ml-auto">
            <connection :status="currentConnection"></connection>
        </div>
    </div>
</nav>