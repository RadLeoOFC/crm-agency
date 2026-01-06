@extends('adminlte::page')

@section('title', 'Bookings')

@section('content_header')
<div class="container">
    <h1 class="mb-3">{{ __('messages.bookings.title') }}</h1>

    <div class="mb-3">
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ {{ __('messages.bookings.add') }}</a>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>{{ __('messages.bookings.fields.platform') }}</th>
                <th>{{ __('messages.bookings.fields.client') }}</th>
                <th>{{ __('messages.bookings.fields.period') }}</th>
                <th>{{ __('messages.bookings.fields.price') }}</th>
                <th>{{ __('messages.bookings.fields.status') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->platform->name ?? '-' }}</td>
                    <td>{{ $booking->client->name ?? '-' }}</td>
                    <td>
                        {{ optional($booking->starts_at)->format('d.m.Y H:i') }} —
                        {{ optional($booking->ends_at)->format('d.m.Y H:i') }}
                    </td>
                    <td>{{ number_format($booking->price, 2) }} {{ $booking->currency ?? 'USD' }}</td>
                    <td>
                        <span class="badge bg-{{ [
                            'pending' => 'secondary',
                            'confirmed' => 'success',
                            'cancelled' => 'danger',
                            'completed' => 'info'
                        ][$booking->status] ?? 'dark' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.bookings.view') }}</a>
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary">{{ __('messages.bookings.edit') }}</a>
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.bookings.delete_confirm') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">{{ __('messages.bookings.no_items') }}</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop
