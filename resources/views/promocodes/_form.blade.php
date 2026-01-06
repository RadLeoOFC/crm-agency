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

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.code') }}</label>
            <input name="code" class="form-control" maxlength="64"
                   value="{{ old('code', $promo->code ?? '') }}" required>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.type') }}</label>
            <select name="discount_type" id="discount_type" class="form-select" required>
                @foreach(['percent'=>__('messages.promocodes.types.percent'),'fixed'=>__('messages.promocodes.types.fixed')] as $discount_type => $label)
                    <option value="{{ $discount_type }}" @selected(old('discount_type',$promo->discount_type ?? '')===$discount_type)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.value') }}</label>
            <input name="discount_value" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('discount_value', $promo->discount_value ?? '') }}" required>
            <small class="text-muted" id="valueHelp">
                Для процента — 10 = 10%. Для фикс. суммы — укажите валюту ниже.
            </small>
        </div>
    </div>

    <div class="row" id="currencyRow" style="display: none;">
        <div class="col-md-3 mb-3">
            <label class="form-label">Валюта (для фикс. суммы)</label>
            <input name="currency" class="form-control" maxlength="3"
                   value="{{ old('currency', $promo->currency ?? '') }}" placeholder="USD">
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.starts_at') }}</label>
            <input name="starts_at" type="datetime-local" class="form-control"
                   value="{{ old('starts_at', optional($promo->starts_at ?? null)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.ends_at') }}</label>
            <input name="ends_at" type="datetime-local" class="form-control"
                   value="{{ old('ends_at', optional($promo->ends_at ?? null)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.min_order') }}</label>
            <input name="min_order_amount" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('min_order_amount', $promo->min_order_amount ?? '') }}">
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.limit_per_client') }}</label>
            <input name="max_uses_per_client" type="number" min="1" class="form-control"
                   value="{{ old('max_uses_per_client', $promo->max_uses_per_client ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.limit_total') }}</label>
            <input name="max_uses" type="number" min="1" class="form-control"
                   value="{{ old('max_uses', $promo->max_uses ?? '') }}">
        </div>

        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.applies_to') }}</label>
            <select name="applies_to" id="applies_to" class="form-select" required>
                @foreach(['global'=>'Глобально','platform'=>'Площадка','price_list'=>'Прайс-лист'] as $v => $label)
                    <option value="{{ $v }}" @selected(old('applies_to',$promo->applies_to ?? 'global')===$v)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3" id="platformSelect" style="display: none;">
            <label class="form-label">{{ __('messages.promocodes.applies.platform') }}</label>
            <select name="platform_id" class="form-select">
                <option value="">— выберите —</option>
                @foreach(($platforms ?? []) as $pl)
                    <option value="{{ $pl->id }}" @selected(old('platform_id',$promo->platform_id ?? '')==$pl->id)>{{ $pl->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 mb-3" id="pricelistSelect" style="display: none;">
            <label class="form-label">{{ __('messages.promocodes.applies.price_list') }}</label>
            <select name="price_list_id" class="form-select">
                <option value="">— выберите —</option>
                @foreach(($pricelists ?? []) as $pl)
                    <option value="{{ $pl->id }}" @selected(old('price_list_id',$promo->price_list_id ?? '')==$pl->id)">
                        #{{ $pl->id }} — {{ $pl->name }} ({{ $pl->platform->name ?? '—' }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.active') }}</label><br>
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promo->is_active ?? true))>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.promocodes.fields.stackable') }}</label><br>
            <input type="checkbox" name="is_stackable" value="1" @checked(old('is_stackable', $promo->is_stackable ?? false))>
        </div>
    </div>

    <button class="btn btn-success">{{ $submitButtonText }}</button>
    <a class="btn btn-link" href="{{ route('promocodes.index') }}">{{ __('messages.cancel') }}</a>
</div>
