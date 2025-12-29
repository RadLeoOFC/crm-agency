@extends('adminlte::page')

@section('title', 'Promocodes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.promocodes.title') }}</h1>
  <a class="btn btn-primary" href="{{ route('promocodes.create') }}">{{ __('messages.create') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
        <th>{{ __('messages.promocodes.fields.code') }}</th>
        <th>{{ __('messages.promocodes.fields.type') }}</th>
        <th>{{ __('messages.promocodes.fields.value') }}</th>
        <th>{{ __('messages.promocodes.fields.window') }}</th>
        <th>{{ __('messages.promocodes.fields.applies_to') }}</th>
        <th>{{ __('messages.promocodes.fields.active') }}</th>
        <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($promocodes as $promocode)
    <tr>
      <td>{{ $promocode->code }}</td>
      <td>{{ $promocode->discount_type }}</td>
      <td>{{ $promocode->discount_value }} {{ $promocode->discount_type==='fixed' ? $promocode->currency : '%' }}</td>
      <td>{{ $promocode->starts_at ?? '—' }} → {{ $promocode->ends_at ?? '—' }}</td>
      <td>{{ $promocode->applies_to }}</td>
      <td>{{ $promocode->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('promocodes.edit',$promocode) }}">{{ __('messages.edit') }}</a>
        <form class="d-inline" method="POST" action="{{ route('promocodes.destroy',$promocode) }}" onsubmit="return confirm('{{ __('messages.promocodes.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $promocodes->links() }}
@endsection
