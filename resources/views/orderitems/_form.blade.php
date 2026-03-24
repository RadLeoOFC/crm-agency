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
        <label for="order_id" class="form-label">Order</label>
        <select name="order_id" id="order_id" class="form-select" required>
            <option value="">Select order</option>
            @foreach($orders as $order)
                <option value="{{ $order->id }}" @selected(old('order_id', $orderitem->order_id ?? '') == $order->id)>
                    {{ $order->id }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="service_id" class="form-label">Service</label>
        <select name="service_id" id="service_id" class="form-select" required>
            <option value="">Select service</option>
            @foreach($services as $service)
                <option value="{{ $service->id }}" @selected(old('service_id', $orderitem->service_id ?? '') == $service->id)>
                    {{ $service->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="row" id="qty">
        <div class="col-md-3 mb-3">
            <label class="form-label">Qty</label>
            <input name="qty" type="number" step="1" min="0" class="form-control"
                   value="{{ old('qty', $orderitem->qty ?? '') }}">
        </div>
    </div>

    <div class="row" id="price">
        <div class="col-md-3 mb-3">
            <label class="form-label">Price</label>
            <input name="price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('price', $orderitem->price ?? '') }}">
        </div>
    </div>

    <div class="row" id="subtotal">
        <div class="col-md-3 mb-3">
            <label class="form-label">Subtotal</label>
            <input name="subtotal" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('subtotal', $orderitem->subtotal ?? '') }}">
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a href="{{ route('orderitems.index') }}" class="btn btn-secondary">Cancel</a>

</div>
