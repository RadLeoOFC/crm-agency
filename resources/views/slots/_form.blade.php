<div class="card">
  <div class="row">
    <div class="col-md-3 mb-3">
      <label class="form-label">День недели (1..7 или пусто)</label>
      <input name="weekday" type="number" min="1" max="7" class="form-control" value="{{ old('weekday') }}">
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Начало (HH:MM)</label>
      <input name="starts_at" type="time" class="form-control" value="{{ old('starts_at') }}" required>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Окончание</label>
      <input name="ends_at" type="time" class="form-control" value="{{ old('ends_at') }}" required>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Цена за слот</label>
      <input name="slot_price" type="number" step="0.01" min="0" class="form-control" value="{{ old('slot_price') }}" required>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3 mb-3">
      <label class="form-label">Ёмкость (шт.)</label>
      <input name="capacity" type="number" min="0" class="form-control" value="{{ old('capacity',0) }}" required>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">Активно</label><br>
      <input type="checkbox" name="is_active" value="1" checked>
    </div>
  </div>
  <button class="btn btn-success">{{ $submitButtonText }}</button>
  <a class="btn btn-link" href="{{ route('rules.index',$pricelist) }}">Отмена</a>
</div>
