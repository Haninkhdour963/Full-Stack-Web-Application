@extends('layouts.masterPage')

@section('content')

<!-- Header Section -->
<div class="container-xxl py-5  page-header mb-5">
    <div class="container my-5 pt-5 pb-4">
        <h1 class="display-3 text-white mb-3 animated slideInDown">Create Job Post</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-uppercase">
                <li class="breadcrumb-item"><a href="#" class="text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-white">Pages</a></li>
                <li class="breadcrumb-item text-white active" aria-current="page">Job</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Header End -->

<!-- Job Post Form Section -->
<div class="container py-5">
    <h2 class="text-center mb-4">Post a New Job</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('page.clients.storeJobPost') }}" class="bg-light p-5 rounded shadow-lg">
        @csrf

        <!-- Job Title & Location -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="jobTitle" class="form-label enhanced-label">Job Title</label>
                <input type="text" class="form-control rounded-3" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label enhanced-label">Location</label>
                <input type="text" class="form-control rounded-3" id="location" name="location" placeholder="Enter location" required>
            </div>
        </div>

        <!-- Job Description -->
        <div class="mb-4">
            <label for="jobDescription" class="form-label enhanced-label">Job Description</label>
            <textarea class="form-control rounded-3" id="jobDescription" name="jobDescription" rows="6" placeholder="Enter job description" required></textarea>
        </div>

        <!-- Job Category -->
        <div class="mb-4">
            <label for="category" class="form-label enhanced-label">Job Category</label>
            <select class="form-select rounded-3" id="category" name="category" required>
            <option selected>Category</option>
                                    <option value="1">Electricity</option>
                                    <option value="2">Plumbing</option>
                                    <option value="3">Air Conditioning</option>
                                    <option value="4">Carpentry</option>
                                    <option value="5">Construction Works</option>
                                    <option value="6">Gypsum Board</option>
                                    <option value="7">Tiling</option>
                                    <option value="8">Plastering</option>
                                    <option value="9">Painting</option>
                                    <option value="10">Special Decorations</option>
                                    <option value="11">Building Restructuring</option>
                                    <option value="12">Organizing and Cleaning Works</option>
                                    <option value="13">General Maintenance</option>
                                    <option value="14">Building Maintenance</option>
                                    <option value="15">Building Decorations</option>
                                    <option value="16">Interior Design</option>
                                    <option value="17">Architectural Design</option>
                                    <option value="18">Structural Design </option>
            </select>
        </div>

        <!-- Budget Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="budgetMin" class="form-label enhanced-label">Min Budget</label>
                <input type="number" class="form-control rounded-3" id="budgetMin" name="budgetMin" placeholder="Enter minimum budget" required>
            </div>
            <div class="col-md-6">
                <label for="budgetMax" class="form-label enhanced-label">Max Budget</label>
                <input type="number" class="form-control rounded-3" id="budgetMax" name="budgetMax" placeholder="Enter maximum budget" required>
            </div>
        </div>

        <div class="row mb-4">
    <div class="col-md-12">
        <label for="duration" class="form-label enhanced-label">Duration Days</label>
        <input type="number" class="form-control rounded-3" id="duration" name="duration" placeholder="Enter Duration" required>
    </div>
</div>


        <!-- Skills Required -->
        <div class="mb-4">
            <label for="skills" class="form-label enhanced-label">Skills Required</label>
            <textarea class="form-control rounded-3" id="skills" name="skills" rows="4" placeholder="List required skills" required></textarea>
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg px-4 py-2 rounded-pill shadow-sm">Post Job</button>
        </div>
    </form>
</div>

@endsection


