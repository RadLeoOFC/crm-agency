@extends('adminlte::page')

@section('title', __('messages.orders.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.orders.title') }}</h1>
  <a href="{{ route('orders.create') }}" class="btn btn-primary">{{ __('messages.orders.add') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ __('messages.orders.fields.client') }}</th>
      <th>{{ __('messages.orders.fields.manager') }}</th>
      <th>{{ __('messages.orders.fields.status') }}</th>
      <th>{{ __('messages.orders.fields.total_amount') }}</th>
      <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($orders as $order)
    <tr>
      <td>{{ $order->client->name}}</td>
      <td>{{ $order->manager->name }}</td>
      <td>{{ $order->status}}</td>
      <td>{{ $order->total_amount}}</td>
      <td class="text-end">
        <div class="btn-group">
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i>
            </a>
            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.orders.confirm_delete') }}')">
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
