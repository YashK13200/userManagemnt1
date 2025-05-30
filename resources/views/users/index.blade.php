@extends('layouts.app')

@section('content')
    <div class="row mb-3">
        <div class="col">
            <h1>User List's</h1>
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
        <!-- <table class="table table-striped table-hover"> -->
            <table id="usersTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="user_{{ $user->id }}">
                        <td class="text-center align-middle">{{ $user->id }}</td>
                        <td class="text-center align-middle">{{ $user->name }}</td>
                        <td class="text-center align-middle">{{ $user->email }}</td>
                        <td class="text-center align-middle">
                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                        <!-- view          -->
                        <i class="bi bi-eye"></i>
</a>
                            <button class="btn btn-sm btn-warning edit-btn" data-id="{{ $user->id }}">
                            <!-- Edit      -->
                            <a href="{{ route('users.edit', $user->id) }}" >
    <i class="bi bi-pencil"></i>
</a>

                            </button>
                            <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}">
                                <!-- Delete -->
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Generates pagination links for users if needed.and remembers the search term when moving on next page -->
@endsection

@section('scripts')
<script>
    // previously It was opening the Modal Form to add a User But I was told to create a Separate Route to Achieve this
    // Functionality So I have Created The Route 8000/user/create
    $(document).ready(function() {
    //     // Show add user modal
    //     $('#addUserBtn').click(function() {
    //         $('#userForm')[0].reset();
    //         $('#user_id').val('');
    //         $('#userModalLabel').text('Add New User');
    //         $('#userModal').modal('show');
    //     });
     
    // Here is The Functionality To achive This as mentioned Above
    $('#addUserBtn').click(function() {
    window.location.href = '/users/create';
});

// DataTables Bootstrtap 5 JS ie Initializing DataTable 07/04/25
$('#usersTable').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: "Search users...",
            search: "", // clears "Search:" label
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: -1 } // make the "Actions" column unsortable
        ]
    });

        
        // Save user (add/edit)
        $('#saveBtn').click(function() {
    var userId = $('#user_id').val();
    var url = userId ? '/users/' + userId : '/users';
    var method = userId ? 'PUT' : 'POST';

    // Clear previous errors
    $('#userForm input').removeClass('is-invalid');
    $('.invalid-feedback').text('');

    $.ajax({
        url: url,
        type: method,
        data: $('#userForm').serialize(),
        success: function(response) {
            $('#userModal').modal('hide');
            
            // Show success message
            toastr.success(response.message);
            
            // Reload the page to see changes
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(xhr) {
            if (xhr.status === 422) { // Validation error
                var errors = xhr.responseJSON.errors;
                $.each(errors, function(key, value) {
                    $('#' + key).addClass('is-invalid');
                    $('#' + key + '_error').text(value[0]);
                });
            } else {
                toastr.error('An error occurred. Please try again.');
            }
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