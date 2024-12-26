@extends('layouts.masterPage')

@section('content')

<!-- Header Start -->
<div class="container-xxl py-5  page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Profile</h1>
        <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Technician </li>
            </ol>
        </nav>
    </div>
</div>
<!-- Header End -->

<div class="container py-5">
    <h2 class="text-center mb-4">Create Your Profile</h2>
    <form action="{{ route('technician.technicians.store') }}" method="POST" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
            </div>
            <div class="col-md-6">
                <label for="skills" class="form-label">Skills</label>
                <input type="text" class="form-control" id="skills" name="skills" placeholder="Enter your skills" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="bio" class="form-label">Short Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="5" placeholder="Tell us about yourself" required></textarea>
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Years of Experience</label>
            <input type="number" class="form-control" id="experience" name="experience" placeholder="Enter your years of experience" required>
        </div>

        <div class="mb-3">
            <label for="availability" class="form-label">Availability</label>
            <input type="text" class="form-control" id="availability" name="availability" placeholder="Enter your availability (e.g., weekdays, weekends)" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Save Profile</button>
        </div>
    </form>
</div>

@endsection
