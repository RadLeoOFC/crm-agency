@extends('adminlte::page')

@section('title', 'Slots')

@section('content')
<h1>{{ __('messages.slots.title') }}</h1>
<form class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="platform_id" class="form-select" onchange="this.form.submit()">
            <option value="">
                {{ __('messages.slots.filters.all_platforms') }}
            </option>
            @foreach($platforms as $platform)
                <option value="{{ $platform->id }}" @selected(request('platform_id')==$platform->id)>
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">
                {{ __('messages.slots.filters.any_status') }}
            </option>
            @foreach(['available','reserved','booked','cancelled'] as $status)
                <option value="{{ $status }}" @selected(request('status')==$status)>
                    {{ __('messages.slots.statuses.' . $status) }}
                </option>
            @endforeach
        </select>
    </div>
</form>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>{{ __('messages.slots.fields.platform') }}</th>
      <th>{{ __('messages.slots.fields.start') }}</th>
      <th>{{ __('messages.slots.fields.end') }}</th>
      <th>{{ __('messages.slots.fields.price') }}</th>
      <th>{{ __('messages.slots.fields.status') }}</th>
      <th>{{ __('messages.slots.fields.capacity_used') }}</th>
      <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($slots as $slot)
    <tr>
      <td>{{ $slot->id }}</td>
      <td>{{ $slot->platform->name ?? '—' }}</td>
      <td>{{ $slot->starts_at }}</td>
      <td>{{ $slot->ends_at }}</td>
      <td>{{ number_format($slot->price,2,'.',' ') }}</td>
      <td>{{ $slot->status }}</td>
      <td>{{ $slot->capacity }}/{{ $slot->used_capacity }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('slots.edit',$slot) }}">{{ __('messages.edit') }}</a>
        <form class="d-inline" method="POST" action="{{ route('slots.destroy',$slot) }}" onsubmit="return confirm('{{ __('messages.slots.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $slots->links() }}
@endsection
