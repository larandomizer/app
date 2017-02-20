@extends('layouts.app')

@section('pageTitle', 'Larandomizer')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('app.cards.connections')
        </div>
        <div class="col-md-4">
            @include('app.cards.server')
        </div>
        <div class="col-md-4">
            @include('app.cards.prizes')
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card card-table">
                <div class="card-header">
                    Prize Pool
                </div>
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Player ID</th>
                            <th>IP Address</th>
                            <th>Time</th>
                            <th width="80">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><i class="mdi mdi-account-circle text-inverse"></i> Gary Bryan</td>
                            <td>gary@bryan.com</td>
                            <td>123456789</td>
                            <td>210.13.71.1</td>
                            <td><i class="mdi mdi-clock"></i> 00:00:00</td>
                            <td><span class="badge badge-block badge-warning">Winner</span></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-account-circle text-info"></i> Anonymous</td>
                            <td>Not Available</td>
                            <td>123456789</td>
                            <td>210.13.71.1</td>
                            <td><i class="mdi mdi-clock"></i> 00:00:00</td>
                            <td><span class="badge badge-block badge-default">Spectator</span></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-account-circle text-primary"></i> Eoghan O'Brien</td>
                            <td>eoghan@artisanscollaborative.com</td>
                            <td>123456789</td>
                            <td>210.13.71.1</td>
                            <td><i class="mdi mdi-clock"></i> 00:00:00</td>
                            <td><span class="badge badge-block badge-info">Waiting</span></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-account-circle text-inverse"></i> Ada Thompson</td>
                            <td>ada@thompson.com</td>
                            <td>123456789</td>
                            <td>210.13.71.1</td>
                            <td><i class="mdi mdi-clock"></i> 00:00:00</td>
                            <td><span class="badge badge-block badge-inverse">Loser</span></td>
                        </tr>
                        <tr>
                            <td><i class="mdi mdi-account-circle text-inverse"></i> Lela Harris</td>
                            <td>lela@harris.com</td>
                            <td>123456789</td>
                            <td>210.13.71.1</td>
                            <td><i class="mdi mdi-clock"></i> 00:00:00</td>
                            <td><span class="badge badge-block badge-success">Ready</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection