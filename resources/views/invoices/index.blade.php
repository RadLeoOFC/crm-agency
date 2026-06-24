@extends('adminlte::page')

@section('title', __('messages.invoices.title'))

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>{{ __('messages.invoices.title') }}</h1>
        <a href="{{ route('invoices.create') }}" class="btn btn-primary">{{ __('messages.invoices.add') }}</a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="clients-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ __('messages.invoices.fields.client_name') }}</th>
                        <th>{{ __('messages.invoices.fields.order_id') }}</th>
                        <th>{{ __('messages.invoices.fields.booking_id') }}</th>
                        <th>{{ __('messages.invoices.fields.currency') }}</th>
                        <th>{{ __('messages.invoices.fields.amount_due') }}</th>
                        <th>{{ __('messages.invoices.fields.status') }}</th>
                        <th>{{ __('messages.invoices.fields.issued_at') }}</th>
                        <th>{{ __('messages.invoices.fields.due_at') }}</th>
                        <th>{{ __('messages.invoices.fields.sent_at') }}</th>
                        <th>{{ __('messages.invoices.fields.paid_at') }}</th>
                        <th>{{ __('messages.invoices.fields.voided_at') }}</th>
                        <th>{{ __('messages.invoices.fields.public_token') }}</th>
                        <th>{{ __('messages.invoices.fields.purpose') }}</th>
                        <th>{{ __('messages.invoices.fields.notes') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->client->name }}</td>
                            <td>{{ $invoice->order->id }}</td>
                            <td>{{ $invoice->booking->platform->name }}</td>
                            <td>{{ $invoice->currency }}</td>
                            <td>{{ $invoice->amount_due }}</td>
                            <td>{{ $invoice->status }}</td>
                            <td>{{ $invoice->issued_at}}</td>
                            <td>{{ $invoice->due_at}}</td>
                            <td>{{ $invoice->sent_at}}</td>
                            <td>{{ $invoice->paid_at}}</td>
                            <td>{{ $invoice->voided_at}}</td>
                            <td>{{ $invoice->public_token}}</td>
                            <td>{{ $invoice->purpose}}</td>
                            <td>{{ $invoice->notes}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('messages.invoices.confirm_delete') }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
