<div class="card">
    @php
        $override = $override ?? null;
    @endphp


    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Дата</label>
            <input name="for_date" type="date" class="form-control"
                   value="{{ old('for_date', isset($override)?$override->for_date: '') }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Начало (HH:MM)</label>
            <input name="starts_at" type="time" class="form-control"
                   value="{{ old('starts_at', \Carbon\Carbon::parse($override->starts_at)->format('H:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Окончание</label>
            <input name="ends_at" type="time" class="form-control"
                   value="{{ old('ends_at', \Carbon\Carbon::parse($override->ends_at)->format('H:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Цена за слот</label>
            <input name="slot_price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('slot_price', $override->slot_price ?? '') }}" required>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">Ёмкость (опционально)</label>
            <input name="capacity" type="number" min="0" class="form-control"
                   value="{{ old('capacity', $override->capacity ?? '') }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Активно</label><br>
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $override->is_active ?? true))>
        </div>
    </div>

    <button type="submit" class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('priceoverrides.index', $pricelist) }}">Отмена</a>
</div>
