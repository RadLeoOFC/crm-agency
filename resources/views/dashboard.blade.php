@extends('adminlte::page')

@section('title', __('messages.dashboard.title'))

@section('content')
<div class="dashboard-container">
    <h1 style="font-size: 50px; margin-bottom:30px">
        {{ __('messages.dashboard.welcome') }}
    </h1>

    <div class="button-container">
        <a href="{{ url('/') }}" class="btn btn-primary mb-2">
            {{ __('messages.dashboard.go_home') }}
        </a>
    </div>
</div>
@stop
