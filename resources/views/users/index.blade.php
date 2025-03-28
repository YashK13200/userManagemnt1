@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col">
            <h1>User Management System</h1>
        </div>
        <div class="col text-end">
            <button class="btn btn-primary" id="addUserBtn">
                <i class="bi bi-plus"></i> Add User
            </button>
        </div>
    </div>

    <div class="alert alert-success d-none" id="successAlert"></div>
    <div class="alert alert-danger d-none" id="errorAlert"></div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="user_{{ $user->id }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Show add user modal
        $('#addUserBtn').click(function() {
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#userModalLabel').text('Add New User');
            $('#userModal').modal('show');
        });

        // Save user (add/edit)
        $('#saveBtn').click(function() {
    var userId = $('#user_id').val();
    var url = userId ? '/users/' + userId : '/users';
    var method = userId ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        data: $('#userForm').serialize(),
        success: function(response) {
            if (response.status == 200) {
                $('#userModal').modal('hide');
                showAlert('success', response.message);
                // Reload the page to see changes
                location.reload();
            }
        },
        error: function(xhr) {
            var errors = xhr.responseJSON.errors;
            // Clear previous errors
            $('#userForm input').removeClass('is-invalid');
            $('#userForm .invalid-feedback').text('');
            
            // Display new errors
            $.each(errors, function(key, value) {
                $('#' + key).addClass('is-invalid');
                $('#' + key + '_error').text(value[0]);
            });
        }
    });
});

        // Edit user
        $('.edit-btn').click(function() {
    var userId = $(this).data('id');
    $.get('/users/' + userId + '/edit', function(user) {
        $('#user_id').val(user.id);  // This is crucial for updates
        $('#name').val(user.name);
        $('#email').val(user.email);
        $('#password').val('');
        $('#password_confirmation').val('');
        $('#userModalLabel').text('Edit User');
        $('#userModal').modal('show');
    });
});

        // Delete user confirmation
        var deleteUserId;
        $('.delete-btn').click(function() {
            deleteUserId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        // Confirm delete
        $('#confirmDelete').click(function() {
            $.ajax({
                url: '/users/' + deleteUserId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    showAlert('success', response.message);
                    $('#user_' + deleteUserId).remove();
                },
                error: function(xhr) {
                    showAlert('error', 'Error deleting user');
                }
            });
        });

        // Reset validation errors when modal is closed
        $('#userModal').on('hidden.bs.modal', function() {
            $('#userForm input').removeClass('is-invalid');
            $('#userForm .invalid-feedback').text('');
        });

        // Show alert message
        function showAlert(type, message) {
            var alertDiv = type == 'success' ? $('#successAlert') : $('#errorAlert');
            alertDiv.removeClass('d-none').text(message);
            setTimeout(function() {
                alertDiv.addClass('d-none');
            }, 3000);
        }
    });
</script>
@endsection