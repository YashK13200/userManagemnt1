@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Tasks</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Add Task
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table id = "tasksTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Project</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td class="text-center align-middle">{{ $task->id }}</td>
                        <td class="text-center align-middle">{{ $task->title }}</td>
                        <td class="text-center align-middle">{{ $task->project->name }}</td>
                        <td class="text-center align-middle">
                            <span class="badge 
                                @if($task->status == 'Pending') bg-secondary
                                @elseif($task->status == 'In Progress') bg-warning text-dark
                                @else bg-success
                                @endif">
                                {{ $task->status }}
                            </span>
                        </td >
                        <td class="text-center align-middle">
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> 
                            </a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $tasks->links() }}
</div>
@endsection

@section('scripts')
<script>

$(document).ready(function () {
    $('#tasksTable').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: "Search tasks...",
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