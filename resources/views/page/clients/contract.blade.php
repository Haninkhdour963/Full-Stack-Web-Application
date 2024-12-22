@extends('layouts.masterPage')

@section('content')
  <!-- Header End -->
  <div class="container-xxl py-5 bg-dark page-header mb-5">
            <div class="container my-5 pt-5 pb-4">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Contracts </h1>
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
    <div class="container mt-5">
        <h2>Job Postings</h2>
        @if ($jobPosts->isEmpty())
            <p>No job postings found with the selected status.</p>
        @else
            <table class="table">
                <thead>
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
                            <td>{{ $jobPost->category ? $jobPost->category->name : 'No category' }}</td>
                            <td>${{ number_format($jobPost->budget_min, 2) }} - ${{ number_format($jobPost->budget_max, 2) }}</td>
                            <td>{{ ucfirst($jobPost->status) }}</td>
                            <td>
                                {{-- Check if posted_at is not null before calling format() --}}
                                {{ $jobPost->posted_at ? $jobPost->posted_at->format('M d, Y') : 'Not posted yet' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
