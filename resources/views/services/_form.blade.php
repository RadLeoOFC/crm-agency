<div class="card">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row" id="code">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.services.fields.code') }}</label>
            <input name="code" class="form-control"
                   value="{{ old('code', $service->code ?? '') }}" placeholder="{{ __('messages.services.fields.code') }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label">{{ __('messages.services.fields.name') }}</label>
        <select name="name" id="name" class="form-select">
            @foreach(['SEO' => 'SEO', 'SMM' => 'SMM', 'PPC' =>  'PPC' , 'Dev' => 'Dev' ] as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $service->name ?? 'SEO') == $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="description">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.services.fields.description') }}</label>
            <input name="description" class="form-control"
                   value="{{ old('description', $service->description ?? '') }}" placeholder="{{ __('messages.services.fields.description') }}">
        </div>
    </div>

    <div class="row" id="base_price">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.services.fields.base_price') }}</label>
            <input name="base_price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('base_price', $service->base_price ?? '') }}">
        </div>
    </div>

    <div class="col-md-3 mb-3">
      <label class="form-label">{{ __('messages.services.fields.currency') }}</label>
      <select name="currency" class="form-select" required>
        @foreach(\App\Models\Service::$currencies as $code => $name)
            <option value="{{ $code }}" {{ old('currency', $service->currency ?? '') == $code ? 'selected' : '' }}>
                {{ $code }}
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
                {{ old('is_active', $service->is_active ?? true) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_active">{{ __('messages.services.fields.active') }}</label>
        </div>
        @error('is_active')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('services.index') }}">{{ __('messages.cancel') }}</a>
</div>