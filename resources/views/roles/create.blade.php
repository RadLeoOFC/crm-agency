@extends('adminlte::page')

@section('title', 'New Role')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Add New Role</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary float-right">
                <i class="fas fa-arrow-left"></i> Go Back
            </a>
        </div>
    </div>
@stop

@section('content')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Role</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                
                        <div class="form-group">
                            <label for="name">Role Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                        <div class="form-group">
                            <label>Permissions</label>
                            <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[]" value="{{ $permission->name }}" 
                                       id="permission-{{ $permission->id }}"
                                       @if(in_array($permission->name, old('permissions', []))) checked @endif>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('permissions')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
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
            // Additional JS if needed
        });
    </script>
@stop
