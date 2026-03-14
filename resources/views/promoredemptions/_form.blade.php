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
        <label for="promo_code_id" class="form-label">{{ __('messages.promoredemptions.fields.promocode') }}</label>
        <select name="promo_code_id" id="promo_code_id" class="form-select" required>
            <option value="">{{ __('messages.promoredemptions.fields.promocode_select') }}</option>
            @foreach($promocodes as $promocode)
                <option value="{{ $promocode->id }}" @selected(old('promo_code_id', $promoredemption->promo_code_id ?? '') == $promocode->id)>
                    {{ $promocode->code }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="client_id" class="form-label">{{ __('messages.promoredemptions.fields.client') }}</label>
        <select name="client_id" id="client_id" class="form-select" required>
            <option value="">{{ __('messages.promoredemptions.fields.client_select') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $promoredemption->client_id ?? '') == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="booking_id" class="form-label">{{ __('messages.promoredemptions.fields.booking') }}</label>
        <select name="booking_id" id="booking_id" class="form-select" required>
            <option value="">{{ __('messages.promoredemptions.fields.booking_select') }}</option>
            @foreach($bookings as $booking)
                <option value="{{ $booking->id }}" @selected(old('booking_id', $promoredemption->booking_id ?? '') == $booking->id)>
                    {{ $booking->client->name }} {{ $booking->platform->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="discount_amount">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promoredemptions.fields.discount_amount') }}</label>
            <input name="discount_amount" class="form-control" maxlength="3"
                   value="{{ old('discount_amount', $promoredemption->discount_amount ?? '') }}" placeholder="USD">
        </div>
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">{{ __('messages.promoredemptions.fields.used_at') }}</label>
        <input name="used_at" type="datetime-local" class="form-control"
            value="{{ old('used_at', optional($promoredemption->used_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('promoredemptions.index') }}">{{ __('messages.cancel') }}</a>
</div>