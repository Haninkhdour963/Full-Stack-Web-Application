@extends('layouts.master')

@section('title', 'List Payments')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Payments</h4>
                <div class="table-responsive">
                    <!-- Display Success or Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                                <th>Job</th>
                                <th>Client</th>
                                <th>Technician</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr class="{{ $payment->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $payment->id }}</td>
                                    <td>${{ number_format($payment->amount, 2) }}</td>
                                    <td>
                                        @if($payment->payment_date)
                                            {{ $payment->payment_date->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>{{ $payment->job ? $payment->job->name : 'N/A' }}</td>
                                    <td>{{ $payment->client ? $payment->client->name : 'N/A' }}</td>
                                    <td>{{ $payment->technician ? $payment->technician->name : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $payments->links() }}
</div>
@endsection
