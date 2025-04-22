@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Projects</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Project
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table  id="projectsTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Tasks Count</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td class="text-center align-middle">{{ $project->id }}</td>
                        <td class="text-center align-middle">{{ $project->name }}</td>
                        <td class="text-center align-middle">{{ $project->tasks_count }}</td>
                        <td class="text-center align-middle">
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $projects->links() }}
</div>
@endsection


@section('scripts')
<script>

$(document).ready(function () {
    $('#projectsTable').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: "Search projects...",
            search: "", // clears "Search:" label
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: -1 } // make the "Actions" column unsortable
        ]
    });
});
</script>
@endsection