@extends('adminlte::page')

@section('title', 'Price Rules')

@section('content')
<h1>{{ __('messages.pricerules.title') }} — {{ $pricelist->name }}</h1>
<a href="{{ route('pricerules.create',$pricelist) }}" class="btn btn-primary mb-3">{{ __('messages.pricerules.add') }}</a>
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
    @foreach($rules as $rule)
    <tr>
      <td>{{ $rule->weekday ?? 'Любой' }}</td>
      <td>{{ $rule->starts_at }}</td>
      <td>{{ $rule->ends_at }}</td>
      <td>{{ number_format($rule->slot_price,2,'.',' ') }} {{ $pricelist->currency }}</td>
      <td>{{ $rule->capacity }}</td>
      <td>{{ $rule->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('pricerules.edit', [$pricelist, $rule]) }}">{{ __('messages.pricerules.edit') }}</a>
        <form class="d-inline" method="POST" action="{{ route('pricerules.destroy', [$pricelist, $rule]) }}" onsubmit="return confirm('{{ __('messages.pricerules.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<a class="btn btn-link" href="{{ route('pricelists.edit',$pricelist) }}">{{ __('messages.pricerules.back') }}</a>
@endsection
