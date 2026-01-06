@extends('adminlte::page')

@section('title', 'Price Lists')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.pricelists.title') }}</h1>
  <a href="{{ route('pricelists.create') }}" class="btn btn-primary">{{ __('messages.create') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>{{ __('messages.pricelists.fields.platform') }}</th>
      <th>{{ __('messages.pricelists.fields.name') }}</th>
      <th>{{ __('messages.pricelists.fields.currency') }}</th>
      <th>{{ __('messages.pricelists.fields.period') }}</th>
      <th>{{ __('messages.pricelists.fields.timezone') }}</th>
      <th>{{ __('messages.pricelists.fields.active') }}</th>
      <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($pricelists as $pricelist)
    <tr>
      <td>{{ $pricelist->id }}</td>
      <td>{{ $pricelist->platform->name ?? '—' }}</td>
      <td><a href="{{ route('pricelists.edit',$pricelist) }}">{{ $pricelist->name }}</a></td>
      <td>{{ $pricelist->currency }}</td>
      <td>{{ $pricelist->valid_from ?? '—' }} — {{ $pricelist->valid_to ?? '—' }}</td>
      <td>{{ $pricelist->timezone }}</td>
      <td>{{ $pricelist->is_active ? __('messages.yes'):__('messages.no') }}</td>
      <td class="text-end">
        <a href="{{ route('pricelists.show', $pricelist) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.pricelists.view') }}</a>
        <form method="POST" action="{{ route('pricelists.destroy',$pricelist) }}" onsubmit="return confirm('{{ __('messages.pricelists.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $pricelists->links() }}
@endsection
