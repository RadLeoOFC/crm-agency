@extends('adminlte::page')

@section('title', 'Price Lists')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Прайс-листы</h1>
  <a href="{{ route('pricelists.create') }}" class="btn btn-primary">Создать</a>
  <form action="{{ route('pricelists.generateSlots', $pricelist) }}" method="POST">
    @csrf
    <button class="btn btn-primary">
        Generate slots
    </button>
  </form>

</div>
<table class="table table-striped">
  <thead><tr><th>#</th><th>Площадка</th><th>Название</th><th>Валюта</th><th>Период</th><th>TZ</th><th>Активен</th><th></th></tr></thead>
  <tbody>
  @foreach($pricelists as $pricelist)
    <tr>
      <td>{{ $pricelist->id }}</td>
      <td>{{ $pricelist->platform->name ?? '—' }}</td>
      <td><a href="{{ route('pricelists.edit',$pricelist) }}">{{ $pricelist->name }}</a></td>
      <td>{{ $pricelist->currency }}</td>
      <td>{{ $pricelist->valid_from ?? '—' }} — {{ $pricelist->valid_to ?? '—' }}</td>
      <td>{{ $pricelist->timezone }}</td>
      <td>{{ $pricelist->is_active ? 'Да':'Нет' }}</td>
      <td class="text-end">
        <form method="POST" action="{{ route('pricelists.destroy',$pricelist) }}" onsubmit="return confirm('Удалить?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">Удалить</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $pricelists->links() }}
@endsection
