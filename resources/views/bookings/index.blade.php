@extends('adminlte::page')

@section('title', 'Bookings')

@section('content_header')
<div class="container">
    <h1 class="mb-3">Бронирования</h1>

    <div class="mb-3">
        <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ Новое бронирование</a>
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
                <th>Площадка</th>
                <th>Клиент</th>
                <th>Период</th>
                <th>Цена</th>
                <th>Статус</th>
                <th>Действия</th>
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
                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-primary">Просмотр</a>
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-sm btn-outline-secondary">Изменить</a>
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="d-inline" onsubmit="return confirm('Удалить бронирование?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Нет бронирований</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@stop
