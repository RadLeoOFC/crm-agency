@extends('adminlte::page')

@section('title', 'Edit Slot')


@section('content')
<h1>{{ __('messages.slots.edit_title', ['id' => $slot->id]) }}</h1>

<form method="POST" action="{{ route('slots.update',$slot) }}">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.platform') }}</label>
            <select name="platform_id" class="form-select" required>
                @foreach($platforms as $platform)
                    <option value="{{ $platform->id }}" @selected(old('platform_id',$slot->platform_id)==$platform->id)>{{ $platform->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.slots.hints.price_list_optional') }}</label>
            <select name="price_list_id" class="form-select">
                <option value="">{{ __('messages.slots.hints.not_specified') }}</option>
                @foreach($pricelists as $price_list)
                    <option value="{{ $price_list->id }}" @selected(old('price_list_id',$slot->price_list_id)==$platform->id)>
                        #{{ $price_list->id }} — {{ $price_list->name }} ({{ $price_list->platform->name ?? '—' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.status') }}</label>
            <select name="status" class="form-select" required>
                @foreach(['available','reserved','booked','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(old('status',$slot->status)===$status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.start') }}</label>
            <input name="starts_at" type="datetime-local" class="form-control"
                   value="{{ old('starts_at', $slot->starts_at?->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.end') }}</label>
            <input name="ends_at" type="datetime-local" class="form-control"
                   value="{{ old('ends_at', $slot->ends_at?->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.price') }}</label>
            <input name="price" type="number" step="0.01" min="0" class="form-control"
                   value="{{ old('price', $slot->price) }}" required>
        </div>
        <div class="col-md-1 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.capacity') }}</label>
            <input name="capacity" type="number" min="0" class="form-control"
                   value="{{ old('capacity', $slot->capacity) }}" required>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">{{ __('messages.slots.fields.used_capacity') }}</label>
            <input name="used_capacity" type="number" min="0" class="form-control"
                   value="{{ old('used_capacity', $slot->used_capacity) }}" required>
            <small class="text-muted">{{ __('messages.slots.hints.used_le_capacity') }}</small>
        </div>
    </div>

    <button class="btn btn-success">{{ __('messages.save') }}</button>
    <a class="btn btn-link" href="{{ route('slots.index') }}">{{ __('messages.cancel') }}</a>
</form>
@endsection
