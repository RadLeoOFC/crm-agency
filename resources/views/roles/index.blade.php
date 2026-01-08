@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ __('messages.roles.title') }}</h1>
        </div>
        <div class="col-sm-6">
            @can('roles.create')
            <a href="{{ route('roles.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> {{ __('messages.roles.add') }}
            </a>
            @endcan
        </div>
    </div>
@stop

@section('content')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.roles.list') }}</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>{{ __('messages.roles.fields.name') }}</th>
                        <th>{{ __('messages.roles.fields.permissions') }}</th>
                        <th>{{ __('messages.roles.fields.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $permission)
                                <span class="badge badge-secondary">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @can('roles.edit')
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-xs btn-info">
                                <i class="fas fa-edit"></i> {{ __('messages.roles.edit') }}
                            </a>
                            @endcan
                            @can('roles.delete')
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('{{ __('messages.roles.delete_confirm') }}')">
                                    <i class="fas fa-trash"></i> {{ __('messages.roles.delete') }}
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
@stop
