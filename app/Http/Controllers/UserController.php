<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // public function index() 
    // {
    //     $users = User::latest()->paginate(5);
    //     return view('users.index', compact('users'));
    // }

    // Updating index method logic for search feature implemenataion 5/4/2025
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $users = User::latest()->when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->simplePaginate(5);
    
        return view('users.index', compact('users', 'search'));
    }
    

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ],
    [
        'name.required' => 'The Name Field is manadatory'
    ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
           
    // Redirect to users index page with success message
    return redirect()->route('users.index')
    ->with('success', 'User created successfully.');

    // Previously we were doing this after succssful craetion of user but now I am redirecting it to homepage instead of 
    // sending json succsss response msg
        // return response()->json([
        //     'status' => 200,
        //     'message' => 'User created successfully',
        //     'user' => $user
        // ]);
    }

    // public function show(User $user)
    // {
    //     return view('users.show', compact('user'));
    // }
    
    //  Display User Details with Projects
    public function show(User $user)
    {
        $user->load('projects.tasks');
        $availableProjects = Project::whereDoesntHave('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();
    
        return view('users.show', compact('user', 'availableProjects'));
    }
    
    public function assignProject(Request $request, User $user)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
        ]);
    
        $user->projects()->attach($validated['project_id']);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Project assigned successfully.'
        ]);
    }
    
    public function removeProject(User $user, Project $project)
    {
        $user->projects()->detach($project->id);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Project removed successfully.'
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:5|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ],422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => 200,
            'message' => 'User deleted successfully'
        ]);
    }
}