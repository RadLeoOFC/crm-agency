@csrf

<div class="form-group">
    <label for="name">{{ __('messages.users.fields.name') }}</label>
    <input type="text" 
           class="form-control @error('name') is-invalid @enderror" 
           id="name" 
           name="name" 
           value="{{ old('name', $user->name ?? '') }}" 
           required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="email">{{ __('messages.users.fields.email') }}</label>
    <input type="email" 
           class="form-control @error('email') is-invalid @enderror" 
           id="email" 
           name="email" 
           value="{{ old('email', $user->email ?? '') }}" 
           required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="telegram_chat_id">{{ __('messages.users.fields.telegram_chat_id') }}</label>
    <input type="text" 
           class="form-control @error('telegram_chat_id') is-invalid @enderror" 
           id="telegram_chat_id" 
           name="telegram_chat_id" 
           value="{{ old('telegram_chat_id', $user->telegram_chat_id ?? '') }}">
    @error('telegram_chat_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


<div class="form-group">
    <label for="password">{{ __('messages.users.fields.password') }} {{ isset($user) ? "(Leave blank if you don't want to change)" : '' }}</label>
    <input type="password" 
           class="form-control @error('password') is-invalid @enderror" 
           id="password" 
           name="password" 
           {{ !isset($user) ? 'required' : '' }}>
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="roles">{{ __('messages.users.fields.roles') }}</label>
    <select class="form-control @error('roles') is-invalid @enderror" 
            id="roles" 
            name="roles[]" 
            multiple>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" 
                {{ (isset($user) && $user->hasRole($role->name)) ? 'selected' : '' }}>
                {{ $role->name }}
            </option>
        @endforeach
    </select>
    @error('roles')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('js')
<script>
    // Show file name when selected
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var label = e.target.nextElementSibling;
        label.innerHTML = fileName;
    });
</script>
@endpush
