@extends('adminlte::page')

@section('title', 'Promoredemptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.promoredemptions.title') }}</h1>
  <a class="btn btn-primary" href="{{ route('promoredemptions.create') }}">{{ __('messages.create') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
        <th>{{ __('messages.promoredemptions.fields.promocode') }}</th>
        <th>{{ __('messages.promoredemptions.fields.client') }}</th>
        <th>{{ __('messages.promoredemptions.fields.discount_amount') }}</th>
        <th>{{ __('messages.promoredemptions.fields.used_at') }}</th>
        <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($promoredemptions as $promoredemption)
    <tr>
      <td>{{ $promoredemption->promocode->code }}</td>
      <td>{{ $promoredemption->client->name }}</td>
      <td>{{ $promoredemption->discount_amount }}</td>
      <td>{{ $promoredemption->used_at }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('promoredemptions.edit',$promoredemption) }}">{{ __('messages.edit') }}</a>
        <form class="d-inline" method="POST" action="{{ route('promoredemptions.destroy',$promoredemption) }}" onsubmit="return confirm('{{ __('messages.promoredemptions.confirm_delete') }}')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
@endsection