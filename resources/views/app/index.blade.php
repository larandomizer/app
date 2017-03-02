@extends('layouts.app')

@section('pageTitle', 'Larandomizer')

@section('content')
<div v-if="!isRegistered" class="container">
    <div class="row justify-content-center">
        <div class="col-xs-12 col-md-5">
            <div class="card card-form">
                <div class="card-header">Welcome to Prize Pool</div>
                <div class="card-body">
                    <join-form :registration="registered"></join-form>
                </div>
            </div>
        </div>
    </div>
</div>

<div v-if="isRegistered" class="container">
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
<modal v-if="isRegistered" :status="showWinnerModal" v-on:close="showWinnerModal = false">
    <div class="row">
        <div class="col-3 d-flex justify-content-center">
            <span class="mdi mdi-trophy-variant-outline text-warning icon-large d-inline-flex align-self-start"></span>
        </div>
        <div class="col-9">
            <h5>Congratulations! You won!</h5>
            <p>How about that? You won a <strong v-text="'Prize Name'"></strong> which was graciously provided by <strong v-text="'Prize Sponsor'"></strong>. Contact the host to claim your prize.</p>
        </div>
    </div>
</modal>
@endsection
