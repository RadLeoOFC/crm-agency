<div class="card">

    @php
        $rule = $rule ?? null;
    @endphp

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.weekday') }}</label>
            <input name="weekday" type="number" min="1" max="7" class="form-control"
                   value="{{ old('weekday', $rule->weekday ?? '') }}">
            <small class="text-muted">{{ __('messages.pricerules.hints.weekday') }}</small>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.starts_at') }} (HH:MM)</label>
            <input name="starts_at" type="time" class="form-control"
                   value="{{ old('starts_at', \Carbon\Carbon::parse($rule->starts_at)->format('H:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.ends_at') }}</label>
            <input name="ends_at" type="time" class="form-control"
                   value="{{ old('ends_at', \Carbon\Carbon::parse($rule->ends_at)->format('H:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.slot_price') }}</label>
            <input name="slot_price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('slot_price', $rule->slot_price ?? '') }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.capacity') }}</label>
            <input name="capacity" type="number" min="0" class="form-control"
                   value="{{ old('capacity', $rule->capacity ?? 0) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.pricerules.fields.is_active') }}</label><br>
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $rule->is_active ?? true))>
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('pricerules.index', $pricelist) }}">{{ __('messages.cancel') }}</a>
</div>