@extends('adminlte::page')

@section('title', 'Price Overrides')

@section('content')
<h1>Исключения — {{ $pricelist->name }}</h1>
<a href="{{ route('priceoverrides.create',$pricelist) }}" class="btn btn-primary mb-3">Добавить исключение</a>
<table class="table table-striped">
  <thead>
    <tr>
        <th>Дата</th>
        <th>С</th>
        <th>По</th>
        <th>Цена</th>
        <th>Ёмкость</th>
        <th>Активно</th>
        <th class="text-end">Действия</th>
    </tr>
  </thead>
  <tbody>
    @foreach($overrides as $override)
    <tr>
      <td>{{ $override->for_date }}</td>
      <td>{{ $override->starts_at }}</td>
      <td>{{ $override->ends_at }}</td>
      <td>{{ number_format($override->slot_price,2,'.',' ') }} {{ $pricelist->currency }}</td>
      <td>{{ $override->capacity ?? '—' }}</td>
      <td>{{ $override->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('priceoverrides.edit', [$pricelist, $override]) }}">Редакт.</a>
        <form class="d-inline" method="POST" action="{{ route('priceoverrides.destroy', [$pricelist, $override]) }}" onsubmit="return confirm('Удалить?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Удалить</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<a class="btn btn-link" href="{{ route('pricelists.edit',$pricelist) }}">Назад к прайс-листу</a>
@endsection
