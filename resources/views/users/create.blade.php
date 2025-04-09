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
    <form id ="usersForm" action="{{ route('users.store') }}" method="POST" class ="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <!-- <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required> -->
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            <div id ="name-error" class="invalid-feedback"></div> 
            <!-- @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            <div id ="email-error" class="invalid-feedback"></div>           
            <!-- <div class="invalid-feedback">Please enter a valid email.</div> -->
            <!-- @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" minlength="5" required>
            <div id ="password-error" class="invalid-feedback"></div>
            <!-- @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror -->
        </div>
        <!-- Confirm Password -->
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
    <div id="confirm-password-error" class="invalid-feedback">
    </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded',function() {
         const form = document.querySelector('#usersForm');  // change to your form's ID
         const nameInput = document.querySelector('#name');  // // update to match your input's ID

         const nameError = document.querySelector('#name-error');
         const emailError = document.querySelector('#email-error');
         const passwordError = document.querySelector('#password-error');
         const confirmpasswordError = document.querySelector('#confirm-password-error');

         form.addEventListener('submit', function(event){
            const rawName = nameInput.value;
            const trimmedName = rawName.trim();

            //  // Regex: Must contain only letters and single spaces between words
            const namePattern = /^[A-Za-z]+(?:\s[A-Za-z]+)*$/;

            if(trimmedName === '' || !namePattern.test(trimmedName)){
                event.preventDefault();
                event.stopPropagation();
                nameInput.classList.add('is-invalid');
                nameError.textContent = 'Please enter a valid name (letters only, no extra spaces).';
                emailError.textContent = 'Please enter a valid name (eg. johndoe123@gmail.com).';
                passwordError.textContent = 'Password must be at least 5 characters.';
                confirmpasswordError.textContent = 'Enter same password as above';
                form.classList.add('was-validated')
            } else{
                nameInput.classList.remove('is-invalid');
                nameInput.classList.add('is-valid');
                nameError.textContent = '';
                emailError.textContent = '';
            }
         });
    });
</script>
@endsection

