@extends('layouts.masterPage')

@section('content')
    <div class="container">
        <!-- Header End -->
        <div class="container-xxl py-5  page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Hire Technicians</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Hire Technicians</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Header End -->

        <!-- Jobs Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Technicians Listing</h1>
                <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
                    <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 ms-0 pb-3 {{ $filterType === 'featured' ? 'active' : '' }}" 
                               href="{{ route('page.clients.hire', ['filter' => 'featured']) }}">
                                <h6 class="mt-n1 mb-0">Featured</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 pb-3 {{ $filterType === 'full_time' ? 'active' : '' }}" 
                               href="{{ route('page.clients.hire', ['filter' => 'full_time']) }}">
                                <h6 class="mt-n1 mb-0">Full Time</h6>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="d-flex align-items-center text-start mx-3 me-0 pb-3 {{ $filterType === 'part_time' ? 'active' : '' }}" 
                               href="{{ route('page.clients.hire', ['filter' => 'part_time']) }}">
                                <h6 class="mt-n1 mb-0">Part Time</h6>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            @foreach($technicians as $technician)
                                <div class="job-item p-4 mb-4">
                                    <div class="row g-4">
                                        <div class="col-sm-12 col-md-8 d-flex align-items-center">
                                            <img class="flex-shrink-0 img-fluid border rounded" 
                                                 src="{{ $technician->user->profile_image ? asset('storage/' . $technician->user->profile_image) : asset('img/default-profile.jpg') }}" 
                                                 alt="{{ $technician->user->name }}'s profile" 
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="text-start ps-4">
                                                <h5 class="mb-3">{{ $technician->user->name }}</h5>
                                                <p class="mb-2">{{ $technician->bio }}</p>
                                                <span class="text-truncate me-3">
                                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $technician->location }}
                                                </span>
                                                <span class="text-truncate me-3">
                                                    <i class="far fa-clock text-primary me-2"></i>
                                                    @if($technician->hourly_rate > 50)
                                                        Featured
                                                    @elseif($technician->hourly_rate >= 30)
                                                        Full Time
                                                    @else
                                                        Part Time
                                                    @endif
                                                </span>
                                                <span class="text-truncate me-0">
                                                    <i class="far fa-money-bill-alt text-primary me-2"></i>${{ number_format($technician->hourly_rate, 2) }}/hour
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4 d-flex flex-column align-items-start align-items-md-end justify-content-center">
                                            <div class="d-flex mb-3">
                                              
                                                <a class="btn btn-primary" href="{{ route('page.clients.hire', ['technician' => $technician->id]) }}">
                                                    Hire Now
                                                </a>
                                            </div>
                                            <small class="text-truncate">
                                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                                Available from: {{ $technician->available_from ? \Carbon\Carbon::parse($technician->available_from)->format('M d, Y') : 'Immediately' }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $technicians->appends(['filter' => $filterType])->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Jobs End -->
    </div>
@endsection