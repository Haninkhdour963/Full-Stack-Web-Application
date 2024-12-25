@extends('layouts.masterPage')

@section('content')

<!-- Header Start -->
<div class="container-xxl py-5 bg-dark page-header mb-5">
    <div class="container my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Bids</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Bids</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Header End -->

<!-- Filter Form Start -->
<div class="container-xxl py-5">
    <div class="container">
        <form method="GET" action="{{ route('page.technicians.bid') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="location" class="form-control" placeholder="Location" value="{{ request('location') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="duration" class="form-control" placeholder="Duration" value="{{ request('duration') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="min_budget" class="form-control" placeholder="Min Budget" value="{{ request('min_budget') }}">
                </div>
                <div class="col-md-2">
                    <input type="number" name="max_budget" class="form-control" placeholder="Max Budget" value="{{ request('max_budget') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Filter Form End -->

<!-- Jobs Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Job Postings List</h1>

        <!-- Job Listings Start -->
        @foreach($jobPostings as $job)
        <div class="job-item p-4 mb-4">
            <div class="row g-4">
                <div class="col-sm-12 col-md-8 d-flex align-items-center">
                    <img class="flex-shrink-0 img-fluid border rounded" 
                         src="{{ asset('storage/' . ($job->client->profile_image ?? 'default.jpg')) }}" 
                         alt="Client Profile Image" 
                         style="width: 80px; height: 80px;">
                    <div class="text-start ps-4">
                        <h5 class="mb-3">{{ $job->title }}</h5>
                        <span class="text-truncate me-3"><i class="fa fa-user text-primary me-2"></i> {{ $job->client->name }}</span>
                        <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i> {{ $job->status }}</span>
                        <span class="text-truncate me-3"><i class="fa fa-map-marker-alt text-primary me-2"></i> {{ $job->location }}</span>
                        <span class="text-truncate me-3"><i class="far fa-clock text-primary me-2"></i>{{ $job->duration }}</span>
                        <span class="text-truncate me-0"><i class="far fa-money-bill-alt text-primary me-2"></i>JOD {{ number_format($job->budget_min, 2) }} - JOD {{ number_format($job->budget_max, 2) }}</span>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                    <div class="d-flex mb-3">
                        <a class="btn btn-light btn-square me-3" href=""><i class="far fa-heart text-primary"></i></a>
                        <a class="btn btn-primary" href="{{ route('page.technicians.bid', $job->id) }}">Start A Bid</a>
                    </div>
                    <small class="text-truncate">
                        <i class="far fa-calendar-alt text-primary me-2"></i>
                        @if($job->created_at)
                            {{ $job->created_at->format('d M, Y') }}
                        @else
                            Not Available
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @endforeach
        <!-- Job Listings End -->

        <!-- Pagination Start -->
        <div class="d-flex justify-content-center">
            {{ $jobPostings->links('vendor.pagination.custom') }} <!-- Display pagination links -->
        </div>
        <!-- Pagination End -->
    </div>
</div>
<!-- Jobs End -->

@endsection
