@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Бронирование #{{ $booking->id }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <dl class="row">
        <dt class="col-sm-3">Площадка:</dt>
        <dd class="col-sm-9">{{ $booking->platform->name ?? '-' }}</dd>

        <dt class="col-sm-3">Клиент:</dt>
        <dd class="col-sm-9">{{ $booking->client->name ?? '-' }}</dd>

        <dt class="col-sm-3">Период:</dt>
        <dd class="col-sm-9">
            {{ optional($booking->starts_at)->format('d.m.Y H:i') }} —
            {{ optional($booking->ends_at)->format('d.m.Y H:i') }}
        </dd>

        <dt class="col-sm-3">Промокод:</dt>
        <dd class="col-sm-9">
            @if($booking->promo_code_id)
                {{ $booking->promoCode->code }} (скидка: {{ number_format($booking->discount_amount, 2) }})
            @else
                —
            @endif
        </dd>

        <dt class="col-sm-3">Цена:</dt>
        <dd class="col-sm-9">
            {{ number_format($booking->list_price, 2) }} {{ $booking->currency }} → 
            <strong>{{ number_format($booking->price, 2) }} {{ $booking->currency }}</strong>
        </dd>

        <dt class="col-sm-3">Статус:</dt>
        <dd class="col-sm-9">{{ ucfirst($booking->status) }}</dd>

        <dt class="col-sm-3">Комментарий:</dt>
        <dd class="col-sm-9">{{ $booking->notes ?? '—' }}</dd>
    </dl>

    <div class="mt-3">
        <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-primary">Редактировать</a>
        <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>
</div>
@endsection
