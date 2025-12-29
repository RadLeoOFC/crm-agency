@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>{{ __('messages.users.title_edit') }}: {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('users._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">{{ __('messages.users.update_button') }}</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('User edit page loaded!');
    </script>
@stop
