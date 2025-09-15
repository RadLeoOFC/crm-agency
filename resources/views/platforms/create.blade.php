<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Platform</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h2 class="mb-0">Create Platform</h2>
        </div>
        <div class="card-body">
            <a href="{{ route('platforms.index') }}" class="btn btn-secondary mb-3">Back to List</a>

            <form action="{{ route('platforms.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name of flatform</label>
                    <input type="text" name="name" id="start_date" class="form-control" value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Platform Type</label>
                    <select name="type" id="type" class="form-select">
                        <option value="telegram" {{ old('type') == 'telegram' ? 'selected' : '' }}>Telegram</option>
                        <option value="youtube" {{ old('type') == 'youtube' ? 'selected' : '' }}>Youtube</option>
                        <option value="facebook" {{ old('type') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="website" {{ old('type') == 'website' ? 'selected' : '' }}>Website</option>
                    </select>
                    @if ($errors->has('type'))
                        <div class="alert alert-danger mt-2">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="description" id="description" class="form-control" value="{{ old('description') }}">
                </div>

                <div class="mb-3">
                    <label for="base_price" class="form-label">Price</label>
                    <input type="number" step="0.01" name="base_price" id="base_price" class="form-control" value="{{ old('base_price') }}">
                </div>

                <button type="submit" class="btn btn-success">Create platform</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
