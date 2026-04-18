<div class="card">
    @php
        $orderitem = $orderitem ?? null;
    @endphp

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
        <label for="service_id" class="form-label">{{ __('messages.orderitems.fields.service') }}</label>
        <select name="service_id" id="service_id" class="form-select" required>
            <option value="">{{ __('messages.orderitems.fields.service_select') }}</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" @selected(old('service_id', $orderitem->service_id ?? '') == $service->id)>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="qty">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.orderitems.fields.qty') }}</label>
            <input name="qty" type="number" step="1" min="0" class="form-control"
                   value="{{ old('qty', $orderitem->qty ?? '') }}">
        </div>
    </div>

    <div class="row" id="price">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.orderitems.fields.price') }}</label>
            <input name="price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('price', $orderitem->price ?? '') }}">
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a href="{{ route('orderitems.index', $order) }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>

</div>
