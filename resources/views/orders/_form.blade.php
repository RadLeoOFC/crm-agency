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
        <label for="client_id" class="form-label">Client</label>
        <select name="client_id" id="client_id" class="form-select" required>
            <option value="">Client select</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $booking->client_id ?? '') == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="manager_id" class="form-label">Manager</label>
        <select name="manager_id" id="manager_id" class="form-select" required>
            <option value="">Select manager</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('manager_id', $user->name ?? '') == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">{{ __('messages.bookings.fields.status') }}</label>
        <select name="status" id="status" class="form-select">
            @foreach(['new' => 'new', 'in progress' => 'in_progress', 'completed' => 'completed', 'cancelled' =>  'cancelled' ] as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $order->status ?? 'new') == $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="total_amount">
        <div class="col-md-3 mb-3">
            <label class="form-label">Total amount</label>
            <input name="total_amount" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('total_amount', $order->total_amount ?? '') }}">
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>

</div>
