


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
                    <img class="img-fluid" src="{{asset('assetsPages/img/14.gif') }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-3 text-white animated slideInDown mb-4">Find The Perfect Customer That You Deserved</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2"> Together, we can enhance the efficiency of service delivery in the
                                        labor market.</p>
                                    <a href="" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft ">Search A Services</a>
                                    <a href="" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight ">Find A Technician</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="owl-carousel-item position-relative">
                    <img class="img-fluid" src="{{asset('assetsPages/img/15.gif') }}" alt="">
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background: rgba(43, 57, 64, .5);">
                        <div class="container">
                            <div class="row justify-content-start">
                                <div class="col-10 col-lg-8">
                                    <h1 class="display-3 text-white animated slideInDown mb-4">Find The Best technician That Fit You</h1>
                                    <p class="fs-5 fw-medium text-white mb-4 pb-2"> Together, we can enhance the efficiency of service delivery in the
                                        labor market.</p>
                                        <a href="" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft ">Start A Bid</a>
                                        <a href="" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight ">Find A Technician</a>
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
            <form method="GET" action="{{ route('index') }}" style="position: relative;">
                <div class="col-md-10">
                    <div class="row g-2">
                        <!-- Keyword Input -->
                        <div class="col-md-4">
                            <input type="text" class="form-control border-0" name="keyword" placeholder="Keyword" value="{{ request()->get('keyword') }}" />
                        </div>
                        
                        <!-- Category Dropdown -->
                        <div class="col-md-4">
                            <select class="form-select border-0" name="category">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request()->get('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Location Dropdown -->
                        <div class="col-md-4">
                            <select class="form-select border-0" name="location">
                                <option value="">Select Location</option>
                                @foreach(['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Mafraq', 'Karak', 'Salt', 'Ma\'an', 'Tafilah', 'Ajloun', 'Madaba', 'Jerash', 'Al-Balqa', 'Petra'] as $location)
                                    <option value="{{ $location }}" {{ request()->get('location') == $location ? 'selected' : '' }}>
                                        {{ $location }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="fas fa-search position-absolute top-50 end-0 translate-middle-y me-2" style="cursor: pointer;"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Search Button -->
                <div class="col-md-2" style="position: absolute; top: 0; right: 0; bottom: 0; display: flex; justify-content: center; align-items: center;">
                    <button type="submit" class="btn btn-dark border-0 w-75">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Search End -->

<!-- Technician Category Filter -->
<form method="GET" action="{{ route('index') }}">
    <div class="tab-class text-center wow fadeInUp" data-wow-delay="0.3s">
        <ul class="nav nav-pills d-inline-flex justify-content-center border-bottom mb-5">
            <li class="nav-item">
                <button type="submit" name="technician_category" value="featured" class="btn btn-link">Featured</button>
            </li>
            <li class="nav-item">
                <button type="submit" name="technician_category" value="full_time" class="btn btn-link">Full Time</button>
            </li>
            <li class="nav-item">
                <button type="submit" name="technician_category" value="part_time" class="btn btn-link">Part Time</button>
            </li>
        </ul>
    </div>
</form>

<!-- Category Start -->
<div class="container-xxl py-5">
    <div class="container">
        <h1 class="text-center mb-5 wow fadeInUp" data-wow-delay="0.1s">Explore By Category</h1>
        
        <div class="row g-4">
            @foreach($jobPostings as $jobPosting)
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <a class="cat-item rounded p-4" href="category.html">
                    <i class="fa fa-3x fa-mail-bulk text-primary mb-4"></i>
                    <h6 class="mb-3">
                        {{ $jobPosting->category ? $jobPosting->category->category_name : 'No Category' }}
                    </h6>
                    <p class="mb-0">{{ $jobPosting->title }}</p>
                </a>
            </div>
            @endforeach
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
                                <img class="img-fluid w-100" src="{{asset('assetsPages/img/10.gif') }}">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid" src="{{asset('assetsPages/img/11.gif') }}"style="width: 100%;">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid" src="{{asset('assetsPages/img/11.gif') }}" style="width: 100%;">
                            </div>
                         
                            <div class="col-6 text-start">
                                <img class="img-fluid w-100" src="{{asset('assetsPages/img/7.gif') }}"style="width: 100%;">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid w-100" src="{{asset('assetsPages/img/8.gif') }}"style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <h1 class="mb-4">We Help To Get The Best  Technicians And Find customers</h1>
                        <p class="mb-4"> Tas'heel is an innovative web application designed to streamline the recruitment process and
                            foster effective communication between customers and skilled workers across various fields</p>
                        <p><i class="fa fa-check text-primary me-3"></i> Secure Financial Reservation</p>
                        <p><i class="fa fa-check text-primary me-3"></i> Robust Evaluation System</p>
                        <p><i class="fa fa-check text-primary me-3"></i> 24/7 Technical Support</p>
                        <p><i class="fa fa-check text-primary me-3"></i> Comprehensive Guarantees</p>
                        <p><i class="fa fa-check text-primary me-3"></i> User-Friendly Interface</p>
                        <a class="btn btn-primary py-3 px-5 mt-3 " href="">Read More</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

   

        
<!-- index.blade.php -->

<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <h2 class="mb-5">All Reviews</h2>

    <!-- Check if reviews are available and display them -->
    @if(isset($reviews) && $reviews->count() > 0)
        <div class="row">
            @foreach($reviews as $review)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $review->reviewer->name }} 
                                <small class="text-muted">({{ $review->job->title }})</small>
                            </h5>
                            <p class="card-text">{{ $review->review_message }}</p>
                            <p class="card-text">Rating: {{ $review->rate }}/5</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>No reviews available.</p>
    @endif
</div>




        


    </div>
    @endsection