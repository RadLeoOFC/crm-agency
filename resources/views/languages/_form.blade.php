<div class="card">
    <div class="mb-3">
        <label for="code" class="form-label d-block text-start">{{ __('messages.code') }}</label>
        <input type="text" name="code" value="{{ old('code',$language->code ?? '') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label d-block text-start">{{ __('messages.name') }}</label>
        <input type="text" name="name" value="{{ old('name',$language->name ?? '') }}" class="form-control" required>
    </div>
    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('languages.index') }}">Назад</a>
</div>
