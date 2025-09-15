@extends('adminlte::page')

@section('title', 'Edit Domain')

@section('content')

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">Edit Platform</h2>
        </div>
        <div class="card-body">
            <a href="{{ route('platforms.index') }}" class="btn btn-secondary mb-3">Back to List</a>

            <form action="{{ route('platforms.update', $platform) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name of flatform</label>
                    <input type="string" name="name" id="start_date" class="form-control" value="{{ old('name', $platform->name) }}">
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Platform Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="telegram" {{ old('type', $platform->type) == 'telegram' ? 'selected' : '' }}>Telegram</option>
                        <option value="youtube" {{ old('type', $platform->type) == 'youtube' ? 'selected' : '' }}>Youtube</option>
                        <option value="facebook" {{ old('type', $platform->type) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="website" {{ old('type', $platform->type) == 'website' ? 'selected' : '' }}>Website</option>
                    </select>
                    @if ($errors->has('type'))
                        <div class="alert alert-danger mt-2">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description', $platform->description) }}">
                </div>

                <div class="mb-3">
                    <label for="base_price" class="form-label">Price</label>
                    <input type="number" step="0.01" name="base_price" id="base_price" class="form-control" value="{{ old('base_price', $platform->base_price) }}">
                </div>

                <button type="submit" class="btn btn-success">Update platform</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stop