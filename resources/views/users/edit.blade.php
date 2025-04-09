@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col">
            <h1>Edit User</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <form id="usersForm" action="{{ route('users.update', $user->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            <div id="name-error" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            <div id="email-error" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password (leave blank to keep existing)</label>
            <input type="password" class="form-control" id="password" name="password">
            <div id="password-error" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            <div id="confirm-password-error" class="invalid-feedback"></div>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
@endsection