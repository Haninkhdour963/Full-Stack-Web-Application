@extends('layouts.masterPage')

@section('content')

     <!-- Header End -->
     <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Create Job Post </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb text-uppercase">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Pages</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Job</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Header End -->

<div class="container py-5">
    <h2 class="text-center mb-4">Post a New Job</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('page.clients.storeJobPost') }}" class="bg-light p-4 rounded shadow-sm">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="jobTitle" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="jobTitle" name="jobTitle" placeholder="Enter job title" required>
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="jobDescription" class="form-label">Job Description</label>
            <textarea class="form-control" id="jobDescription" name="jobDescription" rows="5" placeholder="Enter job description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Job Category</label>
            <select class="form-control" id="category" name="category" required>
                <option value="1">Plumbing</option>
                <option value="2">Electrical</option>
                <option value="3">Carpentry</option>
                <option value="4">Cleaning</option>
            </select>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="budgetMin" class="form-label">Min Budget</label>
                <input type="number" class="form-control" id="budgetMin" name="budgetMin" placeholder="Enter minimum budget" required>
            </div>
            <div class="col-md-6">
                <label for="budgetMax" class="form-label">Max Budget</label>
                <input type="number" class="form-control" id="budgetMax" name="budgetMax" placeholder="Enter maximum budget" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="skills" class="form-label">Skills Required</label>
            <textarea class="form-control" id="skills" name="skills" rows="3" placeholder="List required skills" required></textarea>
        </div>

       

        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">Post Job</button>
        </div>
    </form>
</div>
@endsection
