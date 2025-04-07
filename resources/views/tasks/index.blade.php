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
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->project->name }}</td>
                        <td>
                            <span class="badge 
                                @if($task->status == 'Pending') bg-secondary
                                @elseif($task->status == 'In Progress') bg-warning text-dark
                                @else bg-success
                                @endif">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td>
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