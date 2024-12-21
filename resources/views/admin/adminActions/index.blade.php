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
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Action Modal -->
<div class="modal fade" id="addAdminActionModal" tabindex="-1" aria-labelledby="addAdminActionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAdminActionModalLabel">Add New Admin Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addAdminActionForm">
          @csrf
          <div class="mb-3">
            <label for="actionType" class="form-label">Action Type</label>
            <select class="form-control" id="actionType" name="action_type" required>
                <option value="ban_user">Ban User</option>
                <option value="approve_profile">Approve Profile</option>
                <option value="resolve_dispute">Resolve Dispute</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label for="targetUser" class="form-label">Target User</label>
            <select class="form-control" id="targetUser" name="target_user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Admin Action</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Admin Action Modal -->
<div class="modal fade" id="editAdminActionModal" tabindex="-1" aria-labelledby="editAdminActionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAdminActionModalLabel">Edit Admin Action</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editAdminActionForm">
          @csrf
          @method('PUT')
          <input type="hidden" id="editAdminActionId" name="id">
          <div class="mb-3">
            <label for="editActionType" class="form-label">Action Type</label>
            <select class="form-control" id="editActionType" name="action_type" required>
                <option value="ban_user">Ban User</option>
                <option value="approve_profile">Approve Profile</option>
                <option value="resolve_dispute">Resolve Dispute</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editDescription" class="form-label">Description</label>
            <textarea class="form-control" id="editDescription" name="description"></textarea>
          </div>
          <div class="mb-3">
            <label for="editTargetUser" class="form-label">Target User</label>
            <select class="form-control" id="editTargetUser" name="target_user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-warning">Update Admin Action</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Open add admin action modal
        document.getElementById('addAdminActionBtn').addEventListener('click', () => {
            new bootstrap.Modal(document.getElementById('addAdminActionModal')).show();
        });

        // Open edit admin action modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const actionId = button.getAttribute('data-id');
                const actionType = button.getAttribute('data-action-type');
                const description = button.getAttribute('data-description');
                const targetUserId = button.getAttribute('data-target-user-id');

                document.getElementById('editAdminActionId').value = actionId;
                document.getElementById('editActionType').value = actionType;
                document.getElementById('editDescription').value = description;
                document.getElementById('editTargetUser').value = targetUserId;

                new bootstrap.Modal(document.getElementById('editAdminActionModal')).show();
            });
        });

        // View admin action details
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const actionId = button.getAttribute('data-id');
                const detailsRow = document.getElementById(`details-row-${actionId}`);
                
                // Toggle visibility of the details row
                if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                    detailsRow.style.display = 'table-row';
                } else {
                    detailsRow.style.display = 'none';
                }
            });
        });

        // Add admin action form submission
        document.getElementById('addAdminActionForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                const response = await fetch('/admin/actions', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Admin Action added successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while adding the admin action.', 'error');
            }
        });

        // Edit admin action form submission
        document.getElementById('editAdminActionForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                const response = await fetch(`/admin/actions/${document.getElementById('editAdminActionId').value}`, {

                    method: 'PUT',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Admin Action updated successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while updating the admin action.', 'error');
            }
        });

        // Soft delete admin action
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', async () => {
                const actionId = button.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action will soft delete the admin action!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, soft delete it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            const response = await fetch(`/admin/adminActions/${actionId}/soft-delete`, {

                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                if (data.success) {
                                    Swal.fire('Deleted!', 'Admin Action has been soft deleted.', 'success');
                                    const row = document.querySelector(`#admin-action-row-${actionId}`);
                                    row.classList.add('text-muted');
                                    row.querySelector('.soft-delete-btn').setAttribute('disabled', 'true');
                                    row.querySelector('.soft-delete-btn').innerText = 'Deleted';
                                }
                            }
                        } catch (error) {
                            Swal.fire('Error', 'An error occurred while deleting the admin action.', 'error');
                        }
                    }
                });
            });
        });
    });
</script>
@endpush
