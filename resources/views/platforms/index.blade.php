@extends('adminlte::page')

@section('title', 'Platforms')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ __('messages.platforms.title') }}</h1>
        <a href="{{ route('platforms.create') }}" class="btn btn-primary">{{ __('messages.platforms.add') }}</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="platforms-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.platforms.fields.name') }}</th>
                        <th>{{ __('messages.platforms.fields.type') }}</th>
                        <th>{{ __('messages.platforms.fields.description') }}</th>
                        <th>{{ __('messages.platforms.fields.currency') }}</th>
                        <th>{{ __('messages.platforms.fields.timezone') }}</th>
                        <th>{{ __('messages.platforms.fields.status') }}</th>
                        <th>{{ __('messages.platforms.actions.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($platforms as $platform)
                        <tr>
                            <td>{{ $platform->name }}</td>
                            <td>{{ $platform->type }}</td>
                            <td>{{ $platform->description }}</td>
                            <td>{{ $platform->currency }}</td>
                            <td>{{ $platform->timezone }}</td>
                            <td>
                                @if($platform->is_active)
                                    <span class="badge badge-success">{{ __('messages.platforms.fields.active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('messages.platforms.fields.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('platforms.edit', $platform) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('platforms.destroy', $platform) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.platforms.actions.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop