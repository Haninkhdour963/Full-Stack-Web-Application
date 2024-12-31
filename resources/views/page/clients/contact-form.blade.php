@extends('layouts.masterPage')

@section('content')
<!-- Header Start -->
<div class="container-xxl py-5 page-header mb-5" style="background-color: #4A628A;">
    <div class="container my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Contracts</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Sign</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Header End -->

<!-- Contact Technician Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <!-- Form Section Start -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="d-inline-block border rounded-pill text-primary px-4 mb-3">Contact Technician</div>
                <h2 class="mb-4" style="color: #4A628A;">Send a message to {{ $technician->user->name }}</h2>
                <p class="mb-4" style="color: #4A628A;">Please fill out the form below to contact the technician about your project.</p>

                @if($jobPostings->isEmpty())
                    <div class="alert alert-warning">
                        You need to create a job posting first before contacting a technician.
                        <a href="{{ route('page.clients.post') }}" class="btn btn-primary mt-2">Create Job Posting</a>
                    </div>
                @else
                    <form action="{{ route('page.clients.sendMessage') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="technician_id" value="{{ $technician->id }}">

                        <div class="row g-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="job_id" name="job_id" required>
                                        <option value="">Select a job posting</option>
                                        @foreach($jobPostings as $job)
                                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="job_id">Select Job Posting</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" id="message" name="message" placeholder="Message" style="height: 150px" required></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
            <!-- Form Section End -->

            <!-- Technician Details Start -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <div class="card border-rounded" style="background-color: #ffffff;">
                    <h4 class="mb-4" style="color: #4A628A;">Technician Details</h4>
                    <div class="d-flex align-items-center mb-3">
                        <img class="flex-shrink-0 rounded-circle" src="{{ $technician->user->profile_image ? asset('storage/' . $technician->user->profile_image) : asset('img/default-profile.jpg') }}" alt="" style="width: 50px; height: 50px;">
                        <div class="ms-3">
                            <h5 class="mb-0" style="color: #4A628A;">{{ $technician->user->name }}</h5>
                            <span>{{ $technician->location }}</span>
                        </div>
                    </div>
                    <p class="mb-2"><strong style="color: #4A628A;">Hourly Rate:</strong> ${{ number_format($technician->hourly_rate, 2) }}/hour</p>
                    <p class="mb-2"><strong style="color: #4A628A;">Skills:</strong> {{ $technician->skills }}</p>
                    <p class="mb-0"><strong style="color: #4A628A;">Bio:</strong> {{ $technician->bio }}</p>
                </div>
            </div>
            <!-- Technician Details End -->
        </div>
    </div>
</div>
<!-- Contact Technician End -->

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection
