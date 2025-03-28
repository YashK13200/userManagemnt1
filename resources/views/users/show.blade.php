@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col">
            <h1>User Details</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">{{ $user->email }}</h6>
            <p class="card-text">Registered on: {{ $user->created_at->format('M d, Y') }}</p>
            
            <div class="mt-3">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection