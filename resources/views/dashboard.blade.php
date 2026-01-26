@extends('adminlte::page')

@section('title', __('messages.dashboard.title'))

@section('content')

@php
    $bot = config('services.telegram-bot-api.bot_username');
    $link = $bot ? "https://t.me/{$bot}?start=connect" : null;
@endphp

@if(auth()->check() && empty(auth()->user()->telegram_chat_id))
    <div class="alert alert-info">
        <h4 class="alert-heading">{{ __('messages.telegram.connect_title') ?? 'Подключите Telegram' }}</h4>
        <p>{{ __('messages.telegram.connect_text') ?? 'Чтобы получать уведомления, подключите Telegram: откройте бота и нажмите Start, затем поделитесь контактом.' }}</p>

        @if($link)
            <a class="btn btn-primary" href="{{ $link }}" target="_blank" rel="noopener">
                {{ __('messages.telegram.connect_button') ?? 'Подключить Telegram' }}
            </a>
        @else
            <p class="text-danger">TELEGRAM_BOT_USERNAME не задан в .env</p>
        @endif
    </div>
@endif


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
