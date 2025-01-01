    @extends('layouts.masterPage')

    @section('content')
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
            @if(session('current_step', 1) == 2)
    <form method="POST" action="{{ route('page.technicians.acceptContract') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Accept Contract</button>
    </form>
@endif
            @elseif(session('current_step', 1) == 3)
            <h3>Contract Accepted! Now, make the payment.</h3>
            <div id="payment-form">
                <button id="pay-button" class="btn btn-primary">Pay with Stripe</button>
            </div>
            <script src="https://js.stripe.com/v3/"></script>
            <script>
                const stripe = Stripe('{{ env('STRIPE_KEY') }}');
                const payButton = document.getElementById('pay-button');

                payButton.addEventListener('click', async () => {
                    const response = await fetch('{{ route('page.technicians.processPayment', ['bid_id' => session('bid_id')]) }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });

                    const data = await response.json();

                    if (data.success) {
                        const result = await stripe.confirmCardPayment(data.client_secret);
                        if (result.error) {
                            alert('Payment failed.');
                        } else {
                            window.location.href = data.redirect_url;
                        }
                    } else {
                        alert('Payment failed.');
                    }
                });
            </script>
            @elseif(session('current_step', 1) == 4)
            <h3>Payment processed successfully!</h3>
            <a href="{{ route('page.technicians.form') }}" class="btn btn-secondary">Back to Bids</a>
            @endif
        </div>
    </div>
    @endsection