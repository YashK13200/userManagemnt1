@extends('layouts.app')

@section('content')
<!-- <h2>Edit Role</h2> -->
<div class="row mb-3">
        <div class="col">
            <h1>Edit Role</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

<form action="{{ route('roles.update', $role->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Permissions</label><br>
        @foreach($permissions as $permission)
            <div class="form-check form-check-inline">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                    class="form-check-input" id="perm_{{ $permission->id }}">
                <label for="perm_{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
            </div>
        @endforeach
    </div>
    <button class="btn btn-success">Update</button>
</form>
@endsection
