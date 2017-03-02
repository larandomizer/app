@extends('layouts.app')

@section('pageTitle', 'Larandomizer')

@section('content')
<div v-if="!connected" class="container">
    <div class="row justify-content-center">
        <div class="text-center">
            <a v-on:click="connect" class="mdi mdi-power text-primary icon-medium icon-ring d-block m-auto"></a>
            <p class="d-block mt-2">Connection to the server failed</p>
        </div>
    </div>
</div>

<div v-if="connected && !isRegistered" class="container">
    <div class="row justify-content-center">
        <div class="col-xs-12 col-sm-9 col-md-7 col-lg-5">
            <div class="card card-form">
                <div class="card-header">Welcome to Prize Pool</div>
                <div class="card-body">
                    <join-form :registration="registered"></join-form>
                </div>
            </div>
        </div>
    </div>
</div>

<div v-if="connected && isRegistered" class="container">
    <div class="row">
        <div class="col-sm-4">
            <stat name="connections" icon="account-multiple" :title="connections.length" subtitle="Connections" :menu-items="menus.connections"></stat>
        </div>
        <div class="col-sm-4">
            <stat name="uptime" icon="clock" :title="uptimeLabel" subtitle="Server Uptime" :menu-items="menus.server"></stat>
        </div>
        <div class="col-sm-4">
            <stat name="prizes" icon="trophy-variant-outline" :title="prizesLabel" subtitle="Prizes Available" :menu-items="menus.prizes"></stat>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-table">
                <div class="card-header">Prize Pool</div>
                <grid :columns="columns" :records="connections" :connection="connection"></grid>
            </div>
        </div>
    </div>
</div>
<modal v-if="connected && isRegistered" :status="showWinnerModal" v-on:close="showWinnerModal = false">
    <div class="row">
        <div class="col-3 d-flex justify-content-center">
            <span class="mdi mdi-trophy-variant-outline text-warning icon-large d-inline-flex align-self-start"></span>
        </div>
        <div class="col-9">
            <h5>Congratulations! You Won!</h5>
            <p>How about that? You won a <strong v-text="'Prize Name'"></strong> which was graciously provided by <strong v-text="'Prize Sponsor'"></strong>. Contact the host to claim your prize.</p>
        </div>
    </div>
</modal>
<modal v-if="connected && isRegistered" :status="showPasswordModal" v-on:close="showPasswordModal = false">
    <div class="row">
        <div class="col-3 d-flex justify-content-center">
            <span class="mdi mdi-lock text-primary icon-large d-inline-flex align-self-start"></span>
        </div>
        <div class="col-9">
            <h5>What's the Magic Password?</h5>
            <p>Please enter the server password to confirm that you want to do this:</p>
            <form method="post" action="#" v-on:submit="sendAuthentication">
                <div class="form-group">
                    <input type="password" v-model="password" class="form-control" />
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</modal>
@endsection
