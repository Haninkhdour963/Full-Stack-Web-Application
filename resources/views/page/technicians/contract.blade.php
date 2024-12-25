@extends('layouts.masterPage')

@section('content')
    <!-- Header Section -->
    <div class="container-xxl py-5 bg-dark page-header mb-5">
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
    <div class="container">
        <!-- Filter Form -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <form method="GET" action="{{ route('page.technicians.contract') }}" class="form-inline d-flex flex-wrap justify-content-between">
                    <div class="form-group mb-3 col-md-3">
                        <label for="location" class="mr-2 font-weight-bold text-dark">Location:</label>
                        <input type="text" class="form-control rounded-lg" id="location" name="location" value="{{ request('location') }}" placeholder="Enter location">
                    </div>
                    <div class="form-group mb-3 col-md-3">
                        <label for="duration" class="mr-2 font-weight-bold text-dark">Duration:</label>
                        <input type="text" class="form-control rounded-lg" id="duration" name="duration" value="{{ request('duration') }}" placeholder="Enter duration">
                    </div>
                    <div class="form-group mb-3 col-md-3">
                        <label for="min_budget" class="mr-2 font-weight-bold text-dark">Min Budget:</label>
                        <input type="number" class="form-control rounded-lg" id="min_budget" name="min_budget" value="{{ request('min_budget') }}" placeholder="Min budget">
                    </div>
                    <div class="form-group mb-3 col-md-3">
                        <label for="max_budget" class="mr-2 font-weight-bold text-dark">Max Budget:</label>
                        <input type="number" class="form-control rounded-lg" id="max_budget" name="max_budget" value="{{ request('max_budget') }}" placeholder="Max budget">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-lg shadow-sm col-md-auto">Search</button>
                </form>
            </div>
        </div>

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

        <!-- Pagination Links -->
        <div class="d-flex justify-content-end">
            {{ $bids->links('vendor.pagination.custom') }}
        </div>
    </div>
@endsection
