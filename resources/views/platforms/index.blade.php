@extends('adminlte::page')

@section('title', 'Platforms')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Platforms</h1>
        <a href="{{ route('platforms.create') }}" class="btn btn-primary">Add New Platform</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="platforms-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Platform type</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($platforms as $platform)
                        <tr>
                            <td>{{ $platform->name }}</td>
                            <td>{{ $platform->type }}</td>
                            <td>{{ $platform->description }}</td>
                            <td>{{ $platform->base_price }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('platforms.edit', $platform) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('platforms.destroy', $platform) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this platform?')">
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