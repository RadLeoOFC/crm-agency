@extends('adminlte::page')

@section('title', __('messages.services.title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>{{ __('messages.services.title') }}</h1>
  <a href="{{ route('services.create') }}" class="btn btn-primary">{{ __('messages.services.add') }}</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <th>{{ __('messages.services.fields.code') }}</th>
      <th>{{ __('messages.services.fields.name') }}</th>
      <th>{{ __('messages.services.fields.description') }}</th>
      <th>{{ __('messages.services.fields.base_price') }}</th>
      <th>{{ __('messages.services.fields.active') }}</th>
      <th class="text-end">{{ __('messages.actions') }}</th>
    </tr>
  </thead>
  <tbody>
  @foreach($services as $service)
    <tr>
      <td>{{ $service->code ?? '—' }}</td>
      <td>{{ $service->name }}</td>
      <td>{{ $service->description }}</td>
      <td>{{ $service->base_price ?? '—' }} {{ $service->currency }}</td>
      <td>{{ $service->is_active ? __('messages.yes'):__('messages.no') }}</td>
      <td class="text-end">
        <a href="{{ route('services.edit', $service) }}" class="btn btn-warning btn-sm">
          <i class="fas fa-edit"></i>
        </a>
        <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.services.confirm_delete') }}')">
            <i class="fas fa-trash"></i>
          </button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $services->links() }}
@endsection
