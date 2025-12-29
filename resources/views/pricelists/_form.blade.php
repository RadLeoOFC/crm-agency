<div class="card">
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.platform') }}</label>
      <select name="platform_id" class="form-select" required>
        @foreach($platforms as $platform)
          <option value="{{ $platform->id }}" @selected(old('platform_id',$pricelist->platform_id ?? '')==$platform->id)>{{ $platform->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.name') }}</label>
      <input name="name" class="form-control" value="{{ old('name',$pricelist->name ?? '') }}" required>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.currency') }}</label>
      <select name="currency" class="form-select" required>
        @foreach($currencies as $currency)
          <option value="{{ $currency }}" @selected(old('currency',$pricelist->currency ?? '')==$currency)>{{ $currency }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.timezone') }}</label>
      <select name="timezone" class="form-select" required>
        @foreach($timezones as $timezone)
          <option value="{{ $timezone }}" @selected(old('timezone',$pricelist->timezone ?? '')==$timezone)>{{ $timezone }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.slot_duration') }}</label>
      <input name="default_slot_duration" type="number" min="5" max="480" class="form-control" value="{{ old('default_slot_duration',$pricelist->default_slot_duration ?? 60) }}" required>
    </div>
    <div class="col-md-3 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.active') }}</label><br>
      <input type="checkbox" name="is_active" value="1" @checked(old('is_active',$pricelist->is_active ?? true))>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.valid_from') }}</label>
      <input name="valid_from" type="date" class="form-control" value="{{ old('valid_from',$pricelist->valid_from) }}">
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">{{ __('messages.pricelists.fields.valid_to') }}</label>
      <input name="valid_to" type="date" class="form-control" value="{{ old('valid_to',$pricelist->valid_to) }}">
    </div>
  </div>

  <button class="btn btn-success">{{ $submitButtonText }}</button>
  <a class="btn btn-link" href="{{ route('pricelists.index') }}">{{ __('messages.cancel') }}</a>

  @if(!empty($pricelist) && $pricelist->id)
      <hr>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-primary" href="{{ route('pricerules.index',$pricelist) }}">{{ __('messages.pricelists.links.rules') }}</a>
        <a class="btn btn-outline-secondary" href="{{ route('priceoverrides.index',$pricelist) }}">{{ __('messages.pricelists.links.overrides') }}</a>
      </div>
  @endif

</div>
