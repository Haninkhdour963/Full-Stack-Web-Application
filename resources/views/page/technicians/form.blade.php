@extends('layouts.masterPage')

@section('content')
<!-- Form Progress Tracker -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{ session('current_step', 1) * 25 }}%" aria-valuenow="{{ session('current_step', 1) * 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>

<!-- Bid Submission Form -->
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
        <!-- Bid Submitted Confirmation and Next Steps -->
        <h3>Your bid has been submitted for the job "{{ session('job_title') }}".</h3>
        <p>Proceed with accepting the contract to start the job.</p>
        <form method="POST" action="{{ route('page.technicians.acceptContract') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Accept Contract</button>
        </form>
        @elseif(session('current_step', 1) == 3)
        <!-- Payment Form -->
        <h3>Contract Accepted! Now, make the payment.</h3>
        <form action="{{ route('payment') }}" method="POST">
            @csrf
            <input type="hidden" name="price" value="5">
            <input type="hidden" name="product_name" value="bag">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary">Make A Payment</button>
        </form>
        @elseif(session('current_step', 1) == 4)
        <!-- Payment Success -->
        <h3>Payment processed successfully!</h3>
        <a href="{{ route('page.technicians.bid') }}" class="btn btn-secondary">Back to Bids</a>
        @endif
    </div>
</div>

@endsection
