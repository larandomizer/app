@extends('layouts.app')

@section('title', 'Pick a Winner In Realtime With Async PHP')

@section('content')

  @include('partials.connection-failed')
  @include('partials.registration')
  @include('partials.dashboard')
  @include('partials.winner-modal')
  @include('partials.add-prize-modal')
  @include('partials.password-prompt-modal')

@endsection
