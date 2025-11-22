@csrf

<div class="mb-3">
    <label for="platform_id" class="form-label">Площадка</label>
    <select name="platform_id" id="platform_id" class="form-select" required>
        <option value="">Выберите площадку</option>
        @foreach($platforms as $platform)
            <option value="{{ $platform->id }}" @selected(old('platform_id', $booking->platform_id ?? '') == $platform->id)>
                {{ $platform->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="client_id" class="form-label">Клиент</label>
    <select name="client_id" id="client_id" class="form-select" required>
        <option value="">Выберите клиента</option>
        @foreach($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $booking->client_id ?? '') == $client->id)>
                {{ $client->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="starts_at" class="form-label">Дата начала</label>
        <input type="datetime-local" name="starts_at" class="form-control"
            value="{{ old('starts_at', optional($booking->starts_at)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-6 mb-3">
        <label for="ends_at" class="form-label">Дата окончания</label>
        <input type="datetime-local" name="ends_at" class="form-control"
            value="{{ old('ends_at', optional($booking->ends_at)->format('Y-m-d\TH:i')) }}">
    </div>
</div>

<div class="mb-3">
    <label for="promo_code" class="form-label">Промокод</label>
    <input type="text" name="promo_code" class="form-control" value="{{ old('promo_code') }}">
</div>

<div class="mb-3">
    <label for="status" class="form-label">Статус</label>
    <select name="status" id="status" class="form-select">
        @foreach(['pending' => 'Ожидание', 'confirmed' => 'Подтверждено', 'cancelled' => 'Отменено', 'completed' => 'Завершено'] as $key => $label)
            <option value="{{ $key }}" @selected(old('status', $booking->status ?? 'pending') == $key)>
                {{ $label }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="notes" class="form-label">Комментарий</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $booking->notes ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">Сохранить</button>
<a href="{{ route('bookings.index') }}" class="btn btn-secondary">Отмена</a>
