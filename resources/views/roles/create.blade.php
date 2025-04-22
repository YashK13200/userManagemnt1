@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-2xl font-semibold mb-6">Create New Role</h2>
    <div class="col text-end">
        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <!-- Role Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Role Name</label>
            <input type="text" name="name" placeholder="Enter a Role Name" class="form-control" required value="{{ old('name') }}"
                   class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                   @error('name')
        <div class="text-danger mt-1 text-sm">{{ $message }}</div>
    @enderror
        </div>

        <!-- Permissions -->
        <div class="mb-6">
            <label class="form-label block text-gray-700 font-medium mb-2">Permissions</label>

            <!-- All Checkbox -->
            <div class="flex items-center mb-4">
                <input type="checkbox" id="selectAll" class="mr-2 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                <label for="selectAll" class="text-gray-900 font-semibold">All</label>
            </div>

            <!-- Permission Groups -->
            @foreach ($groupedPermissions as $group => $permissions)
                <!-- Group Title -->
                <div class="mb-2 flex items-center">
                    <input type="checkbox" class="group-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-2"
                           data-group="{{ strtolower($group) }}" id="group-{{ strtolower($group) }}">
                    <label for="group-{{ strtolower($group) }}" class="text-gray-800 font-medium">{{ $group }}</label>
                </div>

                <!-- Group Permissions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mb-4 ml-6">
                    @foreach ($permissions as $permission)
                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $permission->name }}"
                                   id="perm-{{ $permission->id }}"
                                   class="perm-checkbox perm-{{ strtolower($group) }} w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mr-2">
                            <label for="perm-{{ $permission->id }}" class="text-gray-700 text-sm">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Submit -->
        <button type="submit"
        class="btn btn-success">Create</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // "Select All" checkbox
    document.getElementById('selectAll').addEventListener('change', function () {
        const checked = this.checked;
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            cb.checked = checked;
        });
    });

    // Group checkbox behavior
    document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
        groupCheckbox.addEventListener('change', function () {
            const group = this.dataset.group;
            const isChecked = this.checked;
            document.querySelectorAll(`.perm-${group}`).forEach(cb => {
                cb.checked = isChecked;
            });
        });
    });
</script>
@endsection
