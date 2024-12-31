@extends('layouts.masterPage')

@section('content')
<div class="container-xxl bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div class="owl-carousel header-carousel position-relative">
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset('assetsPages/img/14.gif') }}" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown mb-4">Find The Perfect Client That You Deserved</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">Together, we can enhance the efficiency of service delivery in the labor market.</p>
                                <a href="{{ route('page.technicians.bid') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Search A Services</a>
                                <a href="{{ route('page.clients.hire') }}" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Find A Technician</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="owl-carousel-item position-relative">
                <img class="img-fluid" src="{{ asset('assetsPages/img/15.gif') }}" alt="">
                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-10 col-lg-8">
                                <h1 class="display-3 text-white animated slideInDown mb-4">Find The Best Technician That Fit You</h1>
                                <p class="fs-5 fw-medium text-white mb-4 pb-2">Together, we can enhance the efficiency of service delivery in the labor market.</p>
                                <a href="{{ route('page.technicians.bid') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Start A Bid</a>
                                <a href="{{ route('page.clients.hire') }}" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Find A Technician</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- Search Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
        <div class="container">
            <div class="row g-2">
                <form id="searchForm" method="GET" action="{{ route('index') }}" class="d-flex">
                    <div class="col-md-10 d-flex">
                        <input type="text" class="form-control border-0" name="keyword" placeholder="Keyword" value="{{ request()->get('keyword') }}" />
                        <select class="form-select border-0 mx-2" name="category">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->get('category') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                        <select class="form-select border-0" name="location">
                            <option value="">Select Location</option>
                            @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Karak', 'Salt', 'Ma\'an', 'Tafilah', 'Ajloun', 'Madaba', 'Jerash', 'Al-Balqa', 'Petra'] as $location)
                                <option value="{{ $location }}" {{ request()->get('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex justify-content-center align-items-center">
                        <button type="submit" class="btn btn-dark border-0 w-75">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- Category Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Explore By Category</h1>
            
            <!-- Results Count -->
            <div class="mb-4" id="resultsCount">
                <p>Found <span class="font-weight-bold">{{ $jobPostings->total() }}</span> results</p>
            </div>
            
            <!-- Job Listings -->
            <div class="row g-4" id="jobListings">
                @foreach($categories as $category)
                    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="cat-item rounded p-4 d-flex flex-column" href="{{ route('page.technicians.bid') }}">
                            <i class="fa fa-3x fa-mail-bulk text-primary mb-4"></i>
                            <h6 class="mb-3">{{ $category->category_name }}</h6>
                            <p class="mb-0">{{ $category->description }}</p>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4" id="pagination">
                {{ $categories->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>
    <!-- Category End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="row g-0 about-bg rounded overflow-hidden">
                        <div class="col-12 text-start">
                            <img class="img-fluid w-100" src="{{ asset('assetsPages/img/10.gif') }}">
                        </div>
                        <div class="col-6 text-start">
                            <img class="img-fluid w-100" src="{{ asset('assetsPages/img/11.gif') }}">
                        </div>
                        <div class="col-6 text-end">
                            <img class="img-fluid w-100" src="{{ asset('assetsPages/img/11.gif') }}">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4">We Help To Get The Best Technicians And Find Customers</h1>
                    <p class="mb-4">Tas'heel is an innovative web application designed to streamline the recruitment process and foster effective communication between customers and skilled workers across various fields.</p>
                    <p><i class="fa fa-check text-primary me-3"></i> Secure Financial Reservation</p>
                    <p><i class="fa fa-check text-primary me-3"></i> Robust Evaluation System</p>
                    <p><i class="fa fa-check text-primary me-3"></i> 24/7 Technical Support</p>
                    <p><i class="fa fa-check text-primary me-3"></i> Comprehensive Guarantees</p>
                    <p><i class="fa fa-check text-primary me-3"></i> User-Friendly Interface</p>
                    <a class="btn btn-primary py-3 px-5 mt-3" href="#">Read More</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
<!-- Testimonial Start -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <h1 class="text-center mb-5">Our Clients and Technicians Say!!!</h1>
        <div class="owl-carousel testimonial-carousel">
            @if(isset($reviews) && $reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="testimonial-item bg-light rounded p-4">
                        <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                        <p>{{ $review->review_message }}</p>
                        <div class="d-flex align-items-center justify-content-center">
                            <!-- <img class="img-fluid flex-shrink-0 rounded" src="{{ asset('path_to_images/'.$review->reviewer->profile_picture) }}" style="width: 50px; height: 50px;"> -->
                            <div class="ps-3 text-center">
                                <h5 class="mb-1">{{ $review->reviewer->name }}</h5>
                                <small>{{ $review->job->title }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p>No reviews available.</p>
            @endif
        </div>
    </div>
</div>
<!-- Testimonial End -->

</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Hide spinner initially
    $('#spinner').addClass('d-none');

    // Handle form input changes (for select boxes and input fields)
    $('#searchForm select, #searchForm input').on('change keyup', function() {
        performSearch();
    });

    // Handle form submission
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        performSearch();
    });

    // Function to perform the AJAX search
    function performSearch() {
        // Show loading spinner
        $('#spinner').removeClass('d-none').addClass('show');
        
        // Get form data
        var formData = $('#searchForm').serialize();
        
        // Perform AJAX request
        $.ajax({
            url: $('#searchForm').attr('action'),
            type: 'GET',
            data: formData,
            success: function(response) {
                // Update job listings and pagination with new content
                if (response.jobListings) {
                    $('#jobListings').html(response.jobListings);
                }
                if (response.pagination) {
                    $('#pagination').html(response.pagination);
                }
                // Update results count
                if (response.count !== undefined) {
                    $('#resultsCount p').html('Found <span class="font-weight-bold">' + response.count + '</span> results');
                }
                
                // Update URL with search parameters without page refresh
                var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + formData;
                window.history.pushState(null, '', newUrl);  // Fixed the missing function call
            }
        });
    }
});
</script>
@endsection
