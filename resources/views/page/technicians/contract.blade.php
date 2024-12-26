@extends('layouts.masterPage')

@section('content')
    <!-- Header Section -->
    <div class="container-xxl py-5  page-header mb-5">
        <div class="container my-5 pt-5 pb-4">
            <h1 class="display-3 text-white mb-3 animated slideInDown">Contracts</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a href="#" class="text-white">Home</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-white">Pages</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Technician</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Header End -->

    <!-- Filter and Table Section -->
   <!-- Filter Form Start -->
<div class="container-xxl py-5">
    <div class="container">
        <!-- Card Wrapper for Filter Form -->
        <div class="card p-4 shadow-sm">
            <h4 class="text-center mb-4">Filter Jobs</h4>
            <form method="GET" action="{{ route('page.technicians.bid') }}" class="mb-4">
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        <input type="text" name="location" class="form-control form-control-lg" placeholder="Location" value="{{ request('location') }}">
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <input type="text" name="duration" class="form-control form-control-lg" placeholder="Duration" value="{{ request('duration') }}">
                    </div>
                    <div class="col-12 col-md-2 mb-3">
                        <input type="number" name="min_budget" class="form-control form-control-lg" placeholder="Min Budget" value="{{ request('min_budget') }}">
                    </div>
                    <div class="col-12 col-md-2 mb-3">
                        <input type="number" name="max_budget" class="form-control form-control-lg" placeholder="Max Budget" value="{{ request('max_budget') }}">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Search A Job </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Card Wrapper End -->
    </div>
</div>
<!-- Filter Form End -->

        <!-- Bids Status Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h2 class="mb-4 font-weight-bold text-dark">Bids Status</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Job Title</th>
                                <th>Technician</th>
                                <th>Bid Amount</th>
                                <th>Status</th>
                                <th>Bid Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bids as $bid)
                                <tr>
                                    <td>{{ $bid->job ? $bid->job->title : 'No Job' }}</td>
                                    <td>{{ optional($bid->technician)->name ?? 'No Technician' }}</td>
                                    <td>${{ number_format($bid->bid_amount, 2) }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($bid->status == 'pending') bg-warning 
                                            @elseif($bid->status == 'accepted') bg-success 
                                            @elseif($bid->status == 'rejected') bg-danger 
                                            @endif">
                                            {{ ucfirst($bid->status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($bid->bid_date)->format('Y-m-d H:i') ?? 'No Date' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Links (Centered) -->
        <div class="d-flex justify-content-center">
            {{ $bids->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
