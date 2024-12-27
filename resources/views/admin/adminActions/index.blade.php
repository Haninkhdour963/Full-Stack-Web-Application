@extends('layouts.master')

@section('title', 'Admin Actions List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Actions List</h4>
                <button class="btn btn-success mb-3" id="addAdminActionBtn">Add New Action</button>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Action Type</th>
                                <th>Description</th>
                                <th>Target User</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="adminActionTableBody">
                            @foreach($adminActions as $adminAction)
                                <tr id="admin-action-row-{{ $adminAction->id }}">
                                    <td>{{ $adminAction->id }}</td>
                                    <td>{{ $adminAction->action_type ?? 'No Action' }}</td>
                                    <td>{{ $adminAction->description ?? 'No Description' }}</td>
                                    <td>{{ optional($adminAction->targetUser)->name ?? 'No User' }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $adminAction->id }}">View</button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $adminAction->id }}" data-action-type="{{ $adminAction->action_type }}" data-description="{{ $adminAction->description }}" data-target-user-id="{{ $adminAction->target_user_id }}">Edit</button>
                                        @if($adminAction->deleted_at)
                                            <button class="btn btn-danger btn-sm" disabled>Deleted</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $adminAction->id }}">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                                <tr id="details-row-{{ $adminAction->id }}" class="details-row" style="display: none;">
                                    <td colspan="5">
                                        <ul>
                                            <li><strong>Action Type:</strong> {{ $adminAction->action_type ?? 'No Type' }}</li>
                                            <li><strong>Description:</strong> {{ $adminAction->description ?? 'No Description' }}</li>
                                            <li><strong>Target User:</strong> {{ optional($adminAction->targetUser)->name ?? 'No User' }}</li>
                                            <li><strong>Created At:</strong> {{ $adminAction->created_at }}</li>
                                            <li><strong>Updated At:</strong> {{ $adminAction->updated_at }}</li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $adminActions->links('vendor.pagination.custom') }}  <!-- This generates the pagination links -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Action Modal -->
<!-- (The modal code stays unchanged) -->

<!-- Edit Admin Action Modal -->
<!-- (The modal code stays unchanged) -->

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // (Your existing JavaScript stays unchanged)
</script>
@endpush
