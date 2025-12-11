@extends('adminlte::page')

@section('title', 'Price Rules')

@section('content')
<h1>Правила — {{ $pricelist->name }}</h1>
<a href="{{ route('pricerules.create',$pricelist) }}" class="btn btn-primary mb-3">Добавить правило</a>
<table class="table table-striped">
  <thead><tr><th>День</th><th>С</th><th>По</th><th>Цена</th><th>Ёмкость</th><th>Активно</th><th class="text-end">Действия</th></tr></thead>
  <tbody>
    @foreach($rules as $rule)
    <tr>
      <td>{{ $rule->weekday ?? 'Любой' }}</td>
      <td>{{ $rule->starts_at }}</td>
      <td>{{ $rule->ends_at }}</td>
      <td>{{ number_format($rule->slot_price,2,'.',' ') }} {{ $pricelist->currency }}</td>
      <td>{{ $rule->capacity }}</td>
      <td>{{ $rule->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <a class="btn btn-sm btn-outline-primary" href="{{ route('pricerules.edit', [$pricelist, $rule]) }}">Редакт.</a>
        <form class="d-inline" method="POST" action="{{ route('pricerules.destroy', [$pricelist, $rule]) }}" onsubmit="return confirm('Удалить?')">
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
