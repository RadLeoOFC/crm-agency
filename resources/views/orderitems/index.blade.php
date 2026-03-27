@extends('adminlte::page')

@section('title', __('messages.orderitems.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.orderitems.title') }}</h1>
  <a href="{{ route('orderitems.create') }}" class="btn btn-primary">{{ __('messages.orderitems.add') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ __('messages.orderitems.fields.order') }}</th>
      <th>{{ __('messages.orderitems.fields.service') }}</th>
      <th>{{ __('messages.orderitems.fields.qty') }}</th>
      <th>{{ __('messages.orderitems.fields.price') }}</th>
      <th>{{ __('messages.orderitems.fields.subtotal') }}</th>
      <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($orderitems as $orderitem)
    <tr>
      <td>{{ $orderitem->order->id}}</td>
      <td>{{ $orderitem->service->name }}</td>
      <td>{{ $orderitem->qty}}</td>
      <td>{{ $orderitem->price}}</td>
      <td>{{ $orderitem->subtotal}}</td>
      <td class="text-end">
        <div class="btn-group">
            <a href="{{ route('orderitems.edit', $orderitem) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('orderitems.destroy', $orderitem) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.orderitems.confirm_delete') }}')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection
