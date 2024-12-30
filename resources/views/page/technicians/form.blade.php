@extends('layouts.masterPage')

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="background-color:#4A628A " width: {{ session('current_step', 1) * 25 }}%" aria-valuenow="{{ session('current_step', 1) * 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        @if(session('current_step', 1) == 1)
        <form method="POST" action="{{ route('page.technicians.submitBid') }}">
            @csrf
            <input type="hidden" name="job_id" value="{{ $job->id ?? '' }}">
            <div class="mb-3">
                <label for="bid_amount" class="form-label">Your Bid Amount</label>
                <input type="number" name="bid_amount" id="bid_amount" class="form-control" required min="1" value="{{ old('bid_amount') }}">
            </div>
            <div class="mb-3">
                <label for="bid_message" class="form-label">Message to Client</label>
                <textarea name="bid_message" id="bid_message" class="form-control" rows="4">{{ old('bid_message') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Bid</button>
        </form>
        @elseif(session('current_step', 1) == 2)
        <h3>Your bid has been submitted for the job "{{ session('job_title') }}".</h3>
        <p>Proceed with accepting the contract to start the job.</p>
        <form method="POST" action="{{ route('page.technicians.acceptContract') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Accept Contract</button>
        </form>
        @elseif(session('current_step', 1) == 3)
        <h3>Contract Accepted! Now, make the payment.</h3>
        <div id="paypal-button-container"></div>
        <script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=JOD"></script>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: "{{ $job->bid_amount }}",
                                currency_code: 'JOD'
                            },
                            description: "Payment for Bid on Job: {{ $job->title }}"
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        fetch('{{ route('success') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ orderID: data.orderID, paymentDetails: details })
                        }).then(response => response.json())
                          .then(responseData => { if (responseData.success) window.location.href = "{{ route('success') }}"; });
                    });
                }
            }).render('#paypal-button-container');
        </script>
        @elseif(session('current_step', 1) == 4)
        <h3>Payment processed successfully!</h3>
        <a href="{{ route('page.technicians.form') }}" class="btn btn-secondary">Back to Bids</a>
        @endif
    </div>
</div>
@endsection
