@extends('adminlte::page')

@section('title', 'Order items')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Order items</h1>
  <a href="{{ route('orderitems.create') }}" class="btn btn-primary">Create new order item</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Order</th>
      <th>Service</th>
      <th>Qty</th>
      <th>Price</th>
      <th>Subtotal</th>
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
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.platforms.actions.confirm_delete') }}')">
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
