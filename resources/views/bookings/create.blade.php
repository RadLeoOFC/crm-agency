@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Новое бронирование</h1>

    <form action="{{ route('bookings.store') }}" method="POST">
        @include('bookings._form', ['booking' => new \App\Models\Booking()])
    </form>
</div>
@endsection
