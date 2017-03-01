@extends('layouts.app')

@section('pageTitle', 'Larandomizer')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <stat name="connections" icon="account-multiple" :title="connections.length" subtitle="Connections" :menu-items="menus.connections"></stat>
        </div>
        <div class="col-md-4">
            <stat name="uptime" icon="clock" :title="uptimeLabel" subtitle="Server Uptime" :menu-items="menus.server"></stat>
        </div>
        <div class="col-md-4">
            <stat name="prizes" icon="trophy-variant-outline" :title="prizesLabel" subtitle="Prizes Available" :menu-items="menus.prizes"></stat>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-table">
                <div class="card-header">Prize Pool</div>
                <grid :columns="columns" :records="connections" :connection="connection"></grid>
            </div>
        </div>
    </div>
</div>
@endsection
