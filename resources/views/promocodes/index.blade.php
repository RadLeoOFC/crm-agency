@extends('adminlte::page')

@section('title', 'Promocodes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Промокоды</h1>
  <a class="btn btn-primary" href="{{ route('promocodes.create') }}">Создать</a>
</div>
<table class="table table-striped">
  <thead>
    <tr>
        <th>Код</th>
        <th>Тип</th>
        <th>Значение</th>
        <th>Окно</th>
        <th>Применение</th>
        <th>Активен</th>
        <th class="text-end">Действия</th>
    </tr>
  </thead>
  <tbody>
  @foreach($promocodes as $promocode)
    <tr>
      <td>{{ $promocode->code }}</td>
      <td>{{ $promocode->discount_type }}</td>
      <td>{{ $promocode->discount_value }} {{ $promocode->discount_type==='fixed' ? $promocode->currency : '%' }}</td>
      <td>{{ $promocode->starts_at ?? '—' }} → {{ $promocode->ends_at ?? '—' }}</td>
      <td>{{ $promocode->applies_to }}</td>
      <td>{{ $promocode->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('promocodes.edit',$promocode) }}">Редакт.</a>
        <form class="d-inline" method="POST" action="{{ route('promocodes.destroy',$promocode) }}" onsubmit="return confirm('Удалить?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Удалить</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $promocodes->links() }}
@endsection
