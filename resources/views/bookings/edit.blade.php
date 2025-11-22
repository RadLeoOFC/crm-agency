@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Редактировать бронирование #{{ $booking->id }}</h1>

    <form action="{{ route('bookings.update', $booking) }}" method="POST">
        @method('PUT')
        @include('bookings._form')
    </form>
</div>
@endsection
