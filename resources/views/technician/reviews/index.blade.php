@extends('layouts.master')

@section('title', 'Reviews List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Reviews List</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Reviewer</th>
                                <th>Reviewee</th>
                                <th>Rating</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr id="review-row-{{ $review->id }}" class="{{ $review->deleted_at ? 'text-muted' : '' }}">
                                    <td>{{ $review->id }}</td>
                                    <td>{{ $review->job->title ?? 'N/A' }}</td>
                                    <td>{{ $review->reviewer->name ?? 'N/A' }}</td>
                                    <td>{{ $review->reviewee->name ?? 'N/A' }}</td>
                                    <td>{{ $review->rate }}</td>
                                    <td>{{ $review->review_message }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center">
    {{ $reviews->links('vendor.pagination.custom') }}
</div>

@endsection
