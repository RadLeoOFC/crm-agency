@extends('adminlte::page')

@section('title', 'Clients')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ __('messages.clients.title') }}</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">{{ __('messages.clients.add') }}</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="clients-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.clients.fields.name') }}</th>
                        <th>{{ __('messages.clients.fields.contact_person') }}</th>
                        <th>{{ __('messages.clients.fields.email') }}</th>
                        <th>{{ __('messages.clients.fields.phone') }}</th>
                        <th>{{ __('messages.clients.fields.company') }}</th>
                        <th>{{ __('messages.clients.fields.vat') }}</th>
                        <th>{{ __('messages.clients.fields.country') }}</th>
                        <th>{{ __('messages.clients.fields.city') }}</th>
                        <th>{{ __('messages.clients.fields.active') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->contact_person }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>{{ $client->company }}</td>
                            <td>{{ $client->vat_number }}</td>
                            <td>{{ $client->country}}</td>
                            <td>{{ $client->city}}</td>
                            <td>
                                @if($client->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.clients.delete_confirm') }}')">
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