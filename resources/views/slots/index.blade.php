@extends('adminlte::page')

@section('title', 'Slots')

@section('content')
<h1>Слоты</h1>
<form class="row g-2 mb-3">
  <div class="col-md-4">
    <select name="platform_id" class="form-select" onchange="this.form.submit()">
      <option value="">Все площадки</option>
      @foreach($platforms as $platform)
        <option value="{{ $platform->id }}" @selected(request('platform_id')==$platform->id)>{{ $platform->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">Любой статус</option>
      @foreach(['available','reserved','booked','cancelled'] as $status)
        <option value="{{ $status }}" @selected(request('status')==$status)>{{ $status }}</option>
      @endforeach
    </select>
  </div>
</form>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Площадка</th>
      <th>Начало</th>
      <th>Конец</th>
      <th>Цена</th>
      <th>Статус</th>
      <th>Кап./Исп.</th>
      <th class="text-end">Действия</th>
    </tr>
  </thead>
  <tbody>
  @foreach($slots as $slot)
    <tr>
      <td>{{ $slot->id }}</td>
      <td>{{ $slot->platform->name ?? '—' }}</td>
      <td>{{ $slot->starts_at }}</td>
      <td>{{ $slot->ends_at }}</td>
      <td>{{ number_format($slot->price,2,'.',' ') }}</td>
      <td>{{ $slot->status }}</td>
      <td>{{ $slot->capacity }}/{{ $slot->used_capacity }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('slots.edit',$slot) }}">Редакт.</a>
        <form class="d-inline" method="POST" action="{{ route('slots.destroy',$slot) }}" onsubmit="return confirm('Удалить слот?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Удалить</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $slots->links() }}
@endsection
