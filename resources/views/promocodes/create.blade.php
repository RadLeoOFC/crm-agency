@extends('adminlte::page')

@section('title', 'Create Promocode')

@section('content_header')
    <h1>Create Promocode</h1>
@stop

@section('content')
    <form action="{{ route('promocodes.store') }}" method="POST">
        @csrf
        @include('promocodes._form', ['submitButtonText' => 'Create promocode'])
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        (function(){
            const type = document.getElementById('discount_type');
            const currencyRow = document.getElementById('currencyRow');
            const applies = document.getElementById('applies_to');
            const platformSelect = document.getElementById('platformSelect');
            const pricelistSelect = document.getElementById('pricelistSelect');

            function toggleCurrency(){
                currencyRow.style.display = (type.value === 'fixed') ? '' : 'none';
            }
            function toggleTargets(){
                platformSelect.style.display = (applies.value === 'platform') ? '' : 'none';
                pricelistSelect.style.display = (applies.value === 'price_list') ? '' : 'none';
            }
            type.addEventListener('change', toggleCurrency);
            applies.addEventListener('change', toggleTargets);
            toggleCurrency(); toggleTargets();
        })();
    </script>
@stop