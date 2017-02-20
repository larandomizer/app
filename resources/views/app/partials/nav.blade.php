<nav class="navbar navbar-light navbar-toggleable-md header">
    <div class="container">
        <a class="navbar-brand" href="{{ config('app.url') }}">{{ config('app.name') }}</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav justify-content-start">
            <a class="nav-link nav-item nav-connected d-flex p-1" href="#">
                <i class="mdi d-inline-flex mdi-account-circle"></i>
                <span class="d-inline-flex align-items-center">Eoghan O'Brien</span>
            </a>
            <notifications :messages="messages"></notifications>
        </div>
        <div class="navbar-nav ml-auto">
            <a class="nav-link nav-item nav-disconnect d-flex p-1 flex-row-reverse" href="#">
                <i class="mdi d-inline-flex mdi-power"></i>
                <span class="d-inline-flex align-items-center">Disconnect</span>
            </a>
        </div>
    </div>
</nav>