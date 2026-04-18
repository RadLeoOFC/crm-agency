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
        <label for="client_id" class="form-label">{{ __('messages.orders.fields.client') }}</label>
        <select name="client_id" id="client_id" class="form-select" required>
            <option value="">{{ __('messages.orders.fields.client_select') }}</option>
            @foreach($clients as $client)
                <option value="{{ $client->id }}" @selected(old('client_id', $booking->client_id ?? '') == $client->id)>
                    {{ $client->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="manager_id" class="form-label">{{ __('messages.orders.fields.manager') }}</label>
        <select name="manager_id" id="manager_id" class="form-select" required>
            <option value="">{{ __('messages.orders.fields.manager_select') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('manager_id', $user->name ?? '') == $user->id)>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">{{ __('messages.orders.fields.status') }}</label>
        <select name="status" id="status" class="form-select">
            @foreach(['new' => 'new', 'in progress' => 'in_progress', 'completed' => 'completed', 'cancelled' =>  'cancelled' ] as $key => $label)
                <option value="{{ $key }}" @selected(old('status', $order->status ?? 'new') == $key)>
                    {{ __('messages.orders.statuses.' . $label) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="total_amount">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.orders.fields.total_amount') }}</label>
            <input name="total_amount" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('total_amount', $order->total_amount ?? '') }}">
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>

    @if(!empty($order) && $order->id)
        <hr>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('orderitems.index',$order) }}">{{ __('messages.orders.links.orderitems') }}</a>
        </div>
    @endif

</div>
