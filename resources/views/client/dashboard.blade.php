@extends('layouts.master')

@section('title', 'Client Dashboard')

@section('content')

<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Notifications</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Message</th>
                            <th>Bid Amount</th>
                            <th>Payment Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach(Auth::user()->notifications as $notification)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $notification->data['message'] ?? 'No message' }}</td>
        <td>JOD {{ number_format($notification->data['bid_amount'] ?? 0, 2) }}</td>
        <td>{{ \Carbon\Carbon::parse($notification->data['payment_date'] ?? now())->format('Y-m-d') }} </td>
       
        <td id="action-{{ $notification->data['bid_id'] ?? 0 }}">
            @if(isset($notification->data['status']) && $notification->data['status'] === 'accepted')
                <span class="badge badge-success">Accepted</span>
            @elseif(isset($notification->data['status']) && $notification->data['status'] === 'rejected')
                <span class="badge badge-danger">Rejected</span>
            @else
                <button href="{{ route('stripe.payment.form') }}" onclick="handleBidResponse({{ $notification->data['bid_id'] ?? 0 }}, 'accept')" class="btn btn-success">Accept</button>
                <button onclick="handleBidResponse({{ $notification->data['bid_id'] ?? 0 }}, 'reject')" class="btn btn-danger">Reject</button>
            @endif
        </td>
    </tr>
@endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-sales">
            <div class="card-body">
                <h4 class="card-title">Total Technicians</h4>
                <p>{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-orders">
            <div class="card-body">
                <h4 class="card-title">Job Postings</h4>
                <p>{{ $totalJobPostings }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-tasks">
            <div class="card-body">
                <h4 class="card-title">Job Bids</h4>
                <p>{{ $totalJobBids }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-tasks">
            <div class="card-body">
                <h4 class="card-title">Total Payments</h4>
                <p>${{ number_format($totalPayments, 2) }}</p> <!-- Display total payments -->
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Users Overview</h4>
                <canvas id="usersChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Job Bids Overview</h4>
                <canvas id="bidsChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Recent Payments</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Job Posting</th>
                            <th>Payment Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($payment->client)
                                        {{ $payment->client->name }}
                                    @else
                                        Unknown Client
                                    @endif
                                </td>
                                <td>
                                    @if($payment->job)
                                        {{ $payment->job->title }}
                                    @else
                                        Unknown Job Posting
                                    @endif
                                </td>
                                <td>JOD {{ number_format($payment->amount, 2) }}</td>
                                <td><span class="badge badge-success">{{ $payment->payment_status }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    // Initialize DataTable for the payments table
    $('table').DataTable();
  });

  // Users Overview Chart
  var ctxUsers = document.getElementById('usersChart').getContext('2d');
  new Chart(ctxUsers, {
      type: 'bar',
      data: {
          labels: @json($months), // Last 6 months
          datasets: [{
              label: 'New Users',
              data: @json($usersChartData), // Dynamic data
              backgroundColor: '#7AB2D3',
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });

  // Job Bids Overview Chart
  var ctxBids = document.getElementById('bidsChart').getContext('2d');
  new Chart(ctxBids, {
      type: 'line',
      data: {
          labels: @json($months), // Last 6 months
          datasets: [{
              label: 'Pending Job Bids',
              data: @json($bidsChartData), // Dynamic data
              borderColor: '#7AB2D3',
              fill: false
          }]
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true
              }
          }
      }
  });
</script>

<script>
function handleBidResponse(bidId, action) {
    fetch(`/technician/handle-bid-response`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ bid_id: bidId, action: action })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (action === 'accept') {
                window.location.href = data.redirect_url; // Redirect for "Accept"
            } else {
                // Update the action cell to show "Rejected"
                document.getElementById(`action-${bidId}`).innerHTML = '<span class="badge badge-danger">Rejected</span>';
            }
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

@endpush