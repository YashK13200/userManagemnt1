@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Task: {{ $task->title }}</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $task->title }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">
                Project: <a href="{{ route('projects.show', $task->project_id) }}">{{ $task->project->name }}</a>
            </h6>
            <p class="card-text">{{ $task->description }}</p>
            <p class="card-text">
                Status: 
                <span class="badge 
                    @if($task->status == 'Pending') bg-secondary
                    @elseif($task->status == 'In Progress') bg-warning text-dark
                    @else bg-success
                    @endif">
                    {{ $task->status }}
                </span>
            </p>
            <p class="card-text">
                <small class="text-muted">
                    Created: {{ $task->created_at->format('M d, Y') }}
                    @if($task->created_at != $task->updated_at)
                        | Last Updated: {{ $task->updated_at->format('M d, Y') }}
                    @endif
                </small>
            </p>
            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>
</div>
@endsection