@extends('layouts.app')

@section('content')
<div class="row mb-3">
    <div class="col">
    <h1>Roles List's</h1>
</div>
<div class = "col text-end">
    <button class= "btn btn-primary" id="addRoleBtn">
    <i class="bi bi-plus"></i> Add Role
</button>
</div>
</div>
    <!-- <a href="{{ route('roles.create') }}" class="btn btn-primary">Create Role</a>
</div> -->

<!-- @if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif -->
<div class="alert alert-success d-none" id="successAlert"></div>
<div class="alert alert-danger d-none" id="errorAlert"></div>

<div class="table-responsive">
    <table id="rolesTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th class="text-center">Name</th>
                <th class="text-center">Permissions</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td class="text-center align-middle">{{ $role->name }}</td>
                <td class="text-center align-middle">{{ implode(', ', $role->permissions->pluck('name')->toArray()) }}</td>
                <td class="text-center align-middle">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#addRoleBtn').click(function() {
    window.location.href = '/roles/create';
});

// DataTables Bootstrtap 5 JS ie Initializing DataTable 07/04/25
$('#rolesTable').DataTable({
        responsive: true,
        language: {
            searchPlaceholder: "Search roles...",
            search: "", // clears "Search:" label
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100],
        columnDefs: [
            { orderable: false, targets: -1 } // make the "Actions" column unsortable
        ]
    });

});
</script>
@endsection
