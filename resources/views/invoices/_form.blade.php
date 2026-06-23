<div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-3">
            <label for="client_id" class="form-label">{{ __('messages.invoices.fields.client') }}</label>
            <select name="client_id" id="client_id" class="form-select" required>
                <option value="">{{ __('messages.invoices.fields.client_select') }}</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(old('client_id', $invoice->client_id ?? '') == $client->id)>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="order_id" class="form-label">{{ __('messages.invoices.fields.order') }}</label>
            <select name="order_id" id="order_id" class="form-select" required>
                <option value="">{{ __('messages.invoices.fields.order_select') }}</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}" @selected(old('order_id', $invoice->order ?? '') == $order->id)>
                        {{ $order->id }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="booking_id" class="form-label">{{ __('messages.invoices.fields.booking') }}</label>
            <select name="booking_id" id="booking_id" class="form-select" required>
                <option value="">{{ __('messages.invoices.fields.booking_select') }}</option>
                @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}" @selected(old('booking_id', $invoice->booking ?? '') == $booking->id)>
                        {{ $booking->platform->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.invoices.fields.currency') }}</label>
            <select name="currency" class="form-select" required>
                @foreach(\App\Models\Invoice::$currencies as $code => $name)
                    <option value="{{ $code }}" {{ old('currency', $invoice->currency ?? '') == $code ? 'selected' : '' }}>
                        {{ $code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row" id="amount_due">
            <div class="col-md-3 mb-3">
                <label class="form-label">{{ __('messages.invoices.fields.amount_due') }}</label>
                <input name="amount_due" type="number" step="0.01" min="0" class="form-control"
                    value="{{ old('amount_due', $invoice->amount_due ?? '') }}">
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.invoices.fields.due_at') }}</label>
            <input name="due_at" type="datetime-local" class="form-control"
                   value="{{ old('due_at', \Carbon\Carbon::parse($invoice->due_at)->format('H:i')) }}" required>
        </div>
        <div class="mb-3">
            <label for="purpose" class="form-label">{{ __('messages.invoices.fields.purpose') }}</label>
            <input type="text" name="purpose" id="purpose" class="form-control" value="{{ old('purpose') }}">
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">{{ __('messages.invoices.fields.notes') }}</label>
            <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}">
        </div>

        <button class="btn btn-success">{{ $submitButtonText }}</button>
        <a class="btn btn-link" href="{{ route('invoices.index') }}">{{ __('messages.cancel') }}</a>
    </div>   
</div>
