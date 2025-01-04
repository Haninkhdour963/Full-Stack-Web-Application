@extends('layouts.master')

@section('title', 'Admin Actions List')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Admin Actions List</h4>
                <!-- تمت إزالة الزر هنا -->
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
                                        <button class="btn btn-primary btn-sm view-details-btn" data-id="{{ $adminAction->id }}" data-action-type="{{ $adminAction->action_type }}" data-description="{{ $adminAction->description }}" data-target-user="{{ optional($adminAction->targetUser)->name }}" data-created-at="{{ $adminAction->created_at }}" data-updated-at="{{ $adminAction->updated_at }}">View</button>
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $adminAction->id }}" data-action-type="{{ $adminAction->action_type }}" data-description="{{ $adminAction->description }}" data-target-user-id="{{ $adminAction->target_user_id }}">Edit</button>
                                        @if($adminAction->deleted_at)
                                            <button class="btn btn-info btn-sm restore-btn" data-id="{{ $adminAction->id }}">Restore</button>
                                        @else
                                            <button class="btn btn-danger btn-sm soft-delete-btn" data-id="{{ $adminAction->id }}">Delete</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="d-flex justify-content-center">
                    {{ $adminActions->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Admin Action Modal -->
<div class="modal fade" id="viewAdminActionModal" tabindex="-1" aria-labelledby="viewAdminActionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewAdminActionModalLabel">Admin Action Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><strong>Action Type:</strong> <span id="viewActionType"></span></p>
        <p><strong>Description:</strong> <span id="viewDescription"></span></p>
        <p><strong>Target User:</strong> <span id="viewTargetUser"></span></p>
        <p><strong>Created At:</strong> <span id="viewCreatedAt"></span></p>
        <p><strong>Updated At:</strong> <span id="viewUpdatedAt"></span></p>
      </div>
    </div>
  </div>
</div>

<!-- Add Admin Action Modal -->
<div class="modal fade" id="addAdminActionModal" tabindex="-1" aria-labelledby="addAdminActionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h5 class="modal-title" id="addAdminActionModalLabel">Add New Admin Action</h5> -->
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
            <textarea class="form-control" id="description" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="targetUser" class="form-label">Target User</label>
            <select class="form-control" id="targetUser" name="target_user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Add Action</button>
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
            <textarea class="form-control" id="editDescription" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editTargetUser" class="form-label">Target User</label>
            <select class="form-control" id="editTargetUser" name="target_user_id" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Update Action</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Pagination Links -->
<div class="d-flex justify-content-center">
    {{ $adminActions->links('vendor.pagination.custom') }}
</div>
@endsection

@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // View admin action details
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', () => {
                const actionId = button.getAttribute('data-id');
                const actionType = button.getAttribute('data-action-type');
                const description = button.getAttribute('data-description');
                const targetUser = button.getAttribute('data-target-user');
                const createdAt = button.getAttribute('data-created-at');
                const updatedAt = button.getAttribute('data-updated-at');

                // Update modal content
                document.getElementById('viewActionType').textContent = actionType;
                document.getElementById('viewDescription').textContent = description;
                document.getElementById('viewTargetUser').textContent = targetUser;
                document.getElementById('viewCreatedAt').textContent = createdAt;
                document.getElementById('viewUpdatedAt').textContent = updatedAt;

                // Show modal
                new bootstrap.Modal(document.getElementById('viewAdminActionModal')).show();
            });
        });

        // Add admin action form submission
        document.getElementById('addAdminActionForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = new FormData(e.target);
            try {
                const response = await fetch('/admin/adminActions', {
                    method: 'POST',
                    body: form,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        Swal.fire('Success', 'Admin action added successfully.', 'success');
                        location.reload();
                    }
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while adding the admin action.', 'error');
            }
        });

        document.getElementById('editAdminActionForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = new FormData(e.target);
    const actionId = document.getElementById('editAdminActionId').value;
    try {
        const response = await fetch(`/admin/adminActions/${actionId}`, {
            method: 'POST',
            body: form,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        const data = await response.json(); // تحويل الاستجابة إلى JSON
        if (response.ok) {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Admin action updated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // تحديث الصف في الجدول
                    const row = document.querySelector(`#admin-action-row-${actionId}`);
                    if (row) {
                        row.querySelector('td:nth-child(2)').textContent = document.getElementById('editActionType').value;
                        row.querySelector('td:nth-child(3)').textContent = document.getElementById('editDescription').value;
                        row.querySelector('td:nth-child(4)').textContent = document.getElementById('editTargetUser').options[document.getElementById('editTargetUser').selectedIndex].text;
                    }

                    // إغلاق الـ Modal
                    const editModal = bootstrap.Modal.getInstance(document.getElementById('editAdminActionModal'));
                    editModal.hide();
                });
            } else {
                Swal.fire('Error', data.message || 'An error occurred while updating the admin action.', 'error');
            }
        } else {
            Swal.fire('Error', data.message || 'An error occurred while updating the admin action.', 'error');
        }
    } catch (error) {
        Swal.fire('Error', error.message || 'An error occurred while updating the admin action.', 'error');
    }
});

        // Open edit admin action modal
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const actionId = button.getAttribute('data-id');
                const actionType = button.getAttribute('data-action-type');
                const description = button.getAttribute('data-description');
                const targetUserId = button.getAttribute('data-target-user-id');

                // Update modal content
                document.getElementById('editAdminActionId').value = actionId;
                document.getElementById('editActionType').value = actionType;
                document.getElementById('editDescription').value = description;
                document.getElementById('editTargetUser').value = targetUserId;

                // Show modal
                new bootstrap.Modal(document.getElementById('editAdminActionModal')).show();
            });
        });

        // Function to handle soft delete
        const softDeleteAdminAction = async (event) => {
            const button = event.target;
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
                                Swal.fire('Deleted!', 'Admin action has been soft deleted.', 'success');
                                
                                // Update the button to "Restore"
                                const row = document.querySelector(`#admin-action-row-${actionId}`);
                                const deleteButton = row.querySelector('.soft-delete-btn');
                                deleteButton.classList.remove('btn-danger', 'soft-delete-btn');
                                deleteButton.classList.add('btn-info', 'restore-btn');
                                deleteButton.innerText = 'Restore';
                                
                                // Update the event listener for the new "Restore" button
                                deleteButton.removeEventListener('click', softDeleteAdminAction);
                                deleteButton.addEventListener('click', restoreAdminAction);
                            }
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while deleting the admin action.', 'error');
                    }
                }
            });
        };

        // Function to handle restore
        const restoreAdminAction = async (event) => {
            const button = event.target;
            const actionId = button.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will restore the admin action!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, restore it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/admin/adminActions/${actionId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.success) {
                                Swal.fire('Restored!', 'Admin action has been restored.', 'success');
                                
                                // Update the button to "Delete"
                                const row = document.querySelector(`#admin-action-row-${actionId}`);
                                const restoreButton = row.querySelector('.restore-btn');
                                restoreButton.classList.remove('btn-info', 'restore-btn');
                                restoreButton.classList.add('btn-danger', 'soft-delete-btn');
                                restoreButton.innerText = 'Delete';
                                
                                // Update the event listener for the new "Delete" button
                                restoreButton.removeEventListener('click', restoreAdminAction);
                                restoreButton.addEventListener('click', softDeleteAdminAction);
                            }
                        }
                    } catch (error) {
                        Swal.fire('Error', 'An error occurred while restoring the admin action.', 'error');
                    }
                }
            });
        };

        // Attach event listeners to existing buttons
        document.querySelectorAll('.soft-delete-btn').forEach(button => {
            button.addEventListener('click', softDeleteAdminAction);
        });

        document.querySelectorAll('.restore-btn').forEach(button => {
            button.addEventListener('click', restoreAdminAction);
        });
    });
</script>
@endpush