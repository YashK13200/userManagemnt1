@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>User: {{ $user->name }}</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                Details
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="projects-tab" data-bs-toggle="tab" data-bs-target="#projects" type="button" role="tab">
                Projects
            </button>
        </li>
    </ul>

    <div class="tab-content p-3 border border-top-0 rounded-bottom">
        <div class="tab-pane fade show active" id="details" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $user->name }}</h5>
                    <p class="card-text">{{ $user->email }}</p>
                    <p class="card-text">
                        <small class="text-muted">
                            Registered: {{ $user->created_at->format('M d, Y') }}
                        </small>
                    </p>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="projects" role="tabpanel">
            <div class="mb-3">
                <h4>Assigned Projects</h4>
                <div class="d-flex">
                    <select class="form-select me-2" id="assignProjectSelect">
                        <option value="">Select Project to Assign</option>
                        @foreach($availableProjects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" id="assignProjectBtn">
                        <i class="bi bi-plus"></i> Assign
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Tasks Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->projects as $project)
                            <tr id="project-{{ $project->id }}">
                                <td>
                                    <a href="{{ route('projects.show', $project->id) }}">{{ $project->name }}</a>
                                </td>
                                <td>{{ $project->tasks->count() }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-project-btn" data-project-id="{{ $project->id }}">
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Assign project to user
        $('#assignProjectBtn').click(function() {
            const projectId = $('#assignProjectSelect').val();
            
            if (!projectId) {
                toastr.error('Please select a project');
                return;
            }

            $.ajax({
                url: "{{ route('users.assign-project', $user->id) }}",
                type: 'POST',
                data: {
                    project_id: projectId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message || 'Error assigning project');
                }
            });
        });

        // Remove project from user
        $('.remove-project-btn').click(function() {
            const projectId = $(this).data('project-id');
            
            if (confirm('Are you sure you want to remove this project from the user?')) {
                $.ajax({
                    url: "{{ route('users.remove-project', [$user->id, '']) }}/" + projectId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $(`#project-${projectId}`).remove();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Error removing project');
                    }
                });
            }
        });
    });
</script>
@endsection
