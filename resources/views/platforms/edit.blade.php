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
                    <label for="currency" class="form-label">Currency</label>
                        <select name="currency" id="currency" class="form-control @error('currency') is-invalid @enderror" required>
                        @foreach(\App\Models\Platform::$currencies as $code => $name)
                            <option value="{{ $code }}" {{ old('currency', $platform->currency ?? '') == $code ? 'selected' : '' }}>
                                {{ $code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="timezone" class="form-label">Timezone</label>
                    <select name="timezone" id="timezone" class="form-select">
                        @foreach($timezones as $tz)
                            <option value="{{ $tz }}" @selected(old('timezone') === $tz)>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" 
                            class="custom-control-input" 
                            id="is_active" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Update platform</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stop