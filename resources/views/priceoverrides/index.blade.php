@extends('adminlte::page')

@section('title', 'Price Overrides')

@section('content')
<h1>{{ __('messages.priceoverrides.title') }} — {{ $pricelist->name }}</h1>
<a href="{{ route('priceoverrides.create',$pricelist) }}" class="btn btn-primary mb-3">{{ __('messages.priceoverrides.add') }}</a>
<table class="table table-striped">
  <thead>
    <tr>
        <th>{{ __('messages.priceoverrides.fields.date') }}</th>
        <th>{{ __('messages.priceoverrides.fields.starts_at') }}</th>
        <th>{{ __('messages.priceoverrides.fields.ends_at') }}</th>
        <th>{{ __('messages.priceoverrides.fields.slot_price') }}</th>
        <th>{{ __('messages.priceoverrides.fields.capacity') }}</th>
        <th>{{ __('messages.priceoverrides.fields.is_active') }}</th>
        <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
    @foreach($overrides as $override)
    <tr>
      <td>{{ $override->for_date }}</td>
      <td>{{ $override->starts_at }}</td>
      <td>{{ $override->ends_at }}</td>
      <td>{{ number_format($override->slot_price,2,'.',' ') }} {{ $pricelist->currency }}</td>
      <td>{{ $override->capacity ?? '—' }}</td>
      <td>{{ $override->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('priceoverrides.edit', [$pricelist, $override]) }}">{{ __('messages.priceoverrides.edit') }}</a>
        <form class="d-inline" method="POST" action="{{ route('priceoverrides.destroy', [$pricelist, $override]) }}" onsubmit="return confirm('{{ __('messages.priceoverrides.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<a class="btn btn-link" href="{{ route('pricelists.edit',$pricelist) }}">{{ __('messages.priceoverrides.back') }}</a>
@endsection
