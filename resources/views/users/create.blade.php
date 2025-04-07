@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col">
            <h1>Create New User</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <!-- Doing Here Client Side Validation using Bootstrap ie. adding in form add-> class ="needs-validation" novalidate 05/04/25 -->
    <form action="{{ route('users.store') }}" method="POST" class ="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            <div class="invalid-feedback">Please enter a name.</div>
            <!-- @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
            <div class="invalid-feedback">Please enter a valid email.</div>           
            <!-- @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="5" required>
            <div class="invalid-feedback">Password must be at least 5 characters.</div>
            <!-- @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            <div class="invalid-feedback">Please confirm your password.</div>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
@endsection

<!-- Adding Bootstrap JS Validation Script for Above Form Validation 5/04/25 6pm-->
@section('scripts')
<script>
    // Bootstrap custom form validation
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation to
        var forms = document.querySelectorAll('.needs-validation')

        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })();
</script>
@endsection
