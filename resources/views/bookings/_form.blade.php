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

    <div class="mb-3">
        <label for="platform_id" class="form-label">{{ __('messages.bookings.fields.platform') }}</label>
        <select name="platform_id" id="platform_id" class="form-select" required>
            <option value="">{{ __('messages.bookings.fields.platform_select') }}</option>
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" @selected(old('platform_id', $booking->platform_id ?? '') == $platform->id)>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="client_id" class="form-label">{{ __('messages.bookings.fields.client') }}</label>
        <select name="client_id" id="client_id" class="form-select" required>
            <option value="">{{ __('messages.bookings.fields.client_select') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $booking->client_id ?? '') == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="starts_at" class="form-label">{{ __('messages.bookings.fields.starts_at') }}</label>
            <input type="datetime-local" name="starts_at" class="form-control"
                value="{{ old('starts_at', optional($booking->starts_at)->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="col-md-6 mb-3">
            <label for="ends_at" class="form-label">{{ __('messages.bookings.fields.ends_at') }}</label>
            <input type="datetime-local" name="ends_at" class="form-control"
                value="{{ old('ends_at', optional($booking->ends_at)->format('Y-m-d\TH:i')) }}">
        </div>
    </div>

    <div class="mb-3">
        <label for="promo_code" class="form-label">{{ __('messages.bookings.fields.promo_code') }}</label>
        <input type="text" name="promo_code" class="form-control" value="{{ old('promo_code') }}">
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">{{ __('messages.bookings.fields.status') }}</label>
        <select name="status" id="status" class="form-select">
            @foreach(['pending' => __('messages.bookings.statuses.pending'), 'confirmed' => __('messages.bookings.statuses.confirmed'), 'cancelled' =>  __('messages.bookings.statuses.cancelled') , 'completed' => __('messages.bookings.statuses.completed') ] as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $booking->status ?? 'pending') == $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="notes" class="form-label">{{ __('messages.bookings.fields.notes') }}</label>
        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $booking->notes ?? '') }}</textarea>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">{{ __('messages.bookings.cancel') }}</a>

</div>
