@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Project: {{ $project->name }}</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <ul class="nav nav-tabs" id="projectTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tasks-tab" data-bs-toggle="tab" data-bs-target="#tasks" type="button" role="tab">
                Tasks
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                Team Members
            </button>
        </li>
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ $project->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            Created: {{ $project->created_at->format('M d, Y') }}
                        </small>
                    </p>
                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="tasks" role="tabpanel">
            <div class="d-flex justify-content-between mb-3">
                <h4>Project Tasks</h4>
                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus"></i> Add Task
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td>
                                    <select class="form-select status-select" data-task-id="{{ $task->id }}">
                                        <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="tab-pane fade" id="users" role="tabpanel">
            <div class="mb-3">
                <h4>Team Members</h4>
                <div class="d-flex">
                    <select class="form-select me-2" id="assignUserSelect">
                        <option value="">Select User to Assign</option>
                        @foreach($availableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" id="assignUserBtn">
                        <i class="bi bi-plus"></i> Assign
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($project->users as $user)
                            <tr id="user-{{ $user->id }}">
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-user-btn" data-user-id="{{ $user->id }}">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Update task status
        $('.status-select').change(function() {
            const taskId = $(this).data('task-id');
            const newStatus = $(this).val();
            
            $.ajax({
                url: `/tasks/${taskId}/update-status`,
                type: 'PUT',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    toastr.error('Error updating task status');
                }
            });
        });

        // Assign user to project
        $('#assignUserBtn').click(function() {
            const userId = $('#assignUserSelect').val();
            
            if (!userId) {
                toastr.error('Please select a user');
                return;
            }

            $.ajax({
                url: `/projects/{{ $project->id }}/assign-user`,
                type: 'POST',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message || 'Error assigning user');
                }
            });
        });

        // Remove user from project
        $('.remove-user-btn').click(function() {
            const userId = $(this).data('user-id');
            
            if (confirm('Are you sure you want to remove this user from the project?')) {
                $.ajax({
                    url: `/projects/{{ $project->id }}/remove-user/${userId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $(`#user-${userId}`).remove();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Error removing user');
                    }
                });
            }
        });
    });
</script>
@endsection
@endsection