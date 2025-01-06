@extends('layouts.master')

@section('title', 'Job Bids')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Job Bids</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <!-- <th>Technician</th> -->
                                <th>Job Posting</th>
                                <th>Location</th>
                                <th>Bid Amount</th>
                                <th>Status</th>
                              
                            </tr>
                        </thead>
                        <tbody id="jobBidsTableBody">
                            @foreach($jobBids as $jobBid)
                                <tr id="jobBid-row-{{ $jobBid->id }}" class="{{ $jobBid->deleted_at ? 'text-muted' : '' }}">
                                    <!-- <td>{{ $jobBid->technician->user->name ?? 'N/A' }}</td> -->
                                    <td>{{ $jobBid->job->title ?? 'N/A' }}</td>
                                    <td>{{ $jobBid->job->location ?? 'N/A' }}</td>
                                    <td>${{ number_format($jobBid->bid_amount, 2) }}</td>
                                    <td>
                                        @if($jobBid->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($jobBid->status == 'accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($jobBid->status == 'declined')
                                            <span class="badge badge-danger">Declined</span>
                                        @elseif($jobBid->status == 'withdrawn')
                                            <span class="badge badge-secondary">Withdrawn</span>
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
</div>
<!-- Add Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $jobBids->links('vendor.pagination.custom') }}
</div>
@endsection