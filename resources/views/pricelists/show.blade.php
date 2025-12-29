@extends('adminlte::page')

@section('title', 'Price List')

@section('content')

{{-- Заголовок + кнопки --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>
        {{ __('messages.pricelists.title_show') }}: {{ $pricelist->name }}
    </h1>

    <div class="d-flex gap-2">
        <a href="{{ route('pricelists.edit', $pricelist) }}" class="btn btn-outline-secondary">
            {{ __('messages.edit') }}
        </a>

        <form action="{{ route('pricelists.generateSlots', $pricelist) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                {{ __('messages.pricelists.generate_slots') }}
            </button>
        </form>
    </div>
</div>

{{-- Флеш-сообщения --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Информация о текущем прайс-листе --}}
<div class="card mb-4">
    <div class="card-header">
        <strong>{{ __('messages.pricelists.information') }}</strong>
    </div>
    <div class="card-body">
        <table class="table table-sm mb-0">
            <tr>
                <th width="200">{{ __('messages.pricelists.fields.platform') }}</th>
                <td>{{ $pricelist->platform->name ?? '—' }}</td>
            </tr>
            <tr>
                <th>{{ __('messages.pricelists.fields.currency') }}</th>
                <td>{{ $pricelist->currency }}</td>
            </tr>
            <tr>
                <th>{{ __('messages.pricelists.fields.period') }}</th>
                <td>
                    {{ $pricelist->valid_from ?? '—' }}
                    —
                    {{ $pricelist->valid_to ?? '—' }}
                </td>
            </tr>
            <tr>
                <th>{{ __('messages.pricelists.fields.timezone') }}</th>
                <td>{{ $pricelist->timezone }}</td>
            </tr>
            <tr>
                <th>{{ __('messages.pricelists.fields.active') }}</th>
                <td>{{ $pricelist->is_active ? 'Да' : 'Нет' }}</td>
            </tr>
        </table>
    </div>
</div>

{{-- Список всех прайс-листов --}}
<div class="card">
    <div class="card-header">
        <strong>{{ __('messages.pricelists.all') }}</strong>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.pricelists.fields.platform') }}</th>
                <th>{{ __('messages.pricelists.fields.name') }}</th>
                <th>{{ __('messages.pricelists.fields.currency') }}</th>
                <th>{{ __('messages.pricelists.fields.period') }}</th>
                <th>{{ __('messages.pricelists.fields.timezone') }}</th>
                <th>{{ __('messages.pricelists.fields.active') }}</th>
                <th class="text-end">{{ __('messages.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pricelists as $list)
                <tr @if($list->id === $pricelist->id) class="table-primary" @endif>
                    <td>{{ $list->id }}</td>
                    <td>{{ $list->platform->name ?? '—' }}</td>
                    <td>
                        <a href="{{ route('pricelists.show', $list) }}">
                            {{ $list->name }}
                        </a>
                    </td>
                    <td>{{ $list->currency }}</td>
                    <td>
                        {{ $list->valid_from ?? '—' }}
                        —
                        {{ $list->valid_to ?? '—' }}
                    </td>
                    <td>{{ $list->timezone }}</td>
                    <td>{{ $list->is_active ? 'Да' : 'Нет' }}</td>
                    <td class="text-end">
                        <form method="POST"
                              action="{{ route('pricelists.destroy', $list) }}"
                              onsubmit="return confirm('{{ __('messages.pricelists.confirm_delete') }}')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{ $pricelists->links() }}
    </div>
</div>

@endsection
