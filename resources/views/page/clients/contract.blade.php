@extends('layouts.masterPage')

@section('content')
<!-- Header Start -->
<div class="container-xxl py-5  page-header mb-5">
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

<!-- Filter Section -->
<div class="container mt-5">
    <form method="GET" action="{{ route('page.clients.contract') }}">
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="status" class="form-label">Job Status</label>
                <select name="status" id="status" class="form-select form-control-lg">
                    <option value="open" {{ $statusFilter == 'open' ? 'selected' : '' }}>Open</option>
                    <!-- <option value="in_progress" {{ $statusFilter == 'in_progress' ? 'selected' : '' }}>In Progress</option> -->
                    <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Completed</option>
                    <!-- <option value="cancelled" {{ $statusFilter == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="closed" {{ $statusFilter == 'closed' ? 'selected' : '' }}>Closed</option> -->
                </select>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <label for="category" class="form-label">Category</label>
                <select name="category" id="category" class="form-select form-control-lg">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $categoryFilter == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100 btn-lg">Apply Filters</button>
            </div>
        </div>
    </form>

    <h2 class="my-4">Job Postings</h2>
    @if ($jobPosts->isEmpty())
        <p>No job postings found with the selected filters.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table"style="background-color: #4A628A;color: white;">
                    <tr>
                        <th>Job Title</th>
                        <th>Location</th>
                        <th>Category</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Posted At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jobPosts as $jobPost)
                        <tr>
                            <td>{{ $jobPost->title }}</td>
                            <td>{{ $jobPost->location }}</td>
                            <td>{{ $jobPost->category ? $jobPost->category->category_name : 'No category' }}</td>
                            <td>${{ number_format($jobPost->budget_min, 2) }} - ${{ number_format($jobPost->budget_max, 2) }}</td>
                            <td>{{ ucfirst($jobPost->status) }}</td>
                            <td>
                                {{ $jobPost->posted_at ? $jobPost->posted_at->format('M d, Y') :now()}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $jobPosts->appends(['status' => $statusFilter, 'category' => $categoryFilter])->links('vendor.pagination.custom') }}
        </div>
    @endif
</div>
@endsection
