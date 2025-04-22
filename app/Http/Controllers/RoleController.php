<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        $groupedPermissions = [];

foreach ($permissions as $permission) {
    $parts = explode(' ', $permission->name);
    $group = ucfirst($parts[1] ?? 'Other'); // E.g., users, projects, tasks
    $groupedPermissions[$group][] = $permission;
}
return view('roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        // $role = Role::create(['name' => $request->name]);
        // $role->syncPermissions($request->permissions);
        // return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array|min:1',
        ]);
    
        DB::beginTransaction();
    
        try {
            // Create the role
            $role = Role::create(['name' => $request->name]);
    
            // Assign permissions
            $role->syncPermissions($request->permissions);
    
            DB::commit();
    
            return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
    
            // Optional: Log the error or display it for debugging
            \Log::error('Role creation failed: ' . $e->getMessage());
    
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.']);
        }
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
