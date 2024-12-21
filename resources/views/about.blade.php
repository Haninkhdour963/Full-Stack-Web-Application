
@extends('layouts.masterPage')


@section('content')

      


      
        <!-- Header End -->
        <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">About Us</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">About</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Header End -->


       
    
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

  
@endsection