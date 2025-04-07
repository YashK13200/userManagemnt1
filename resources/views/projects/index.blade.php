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
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Tasks Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->tasks_count }}</td>
                        <td>
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