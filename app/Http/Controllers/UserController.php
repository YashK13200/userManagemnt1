<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project; 
use Illuminate\Support\Facades\DB;
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
        $users = User::all(); // Fetch all users
    return view('users.index', compact('users'));

        // $search = $request->input('search');

        // $users = User::query()
        //             ->when($search, function ($query, $search) {
        //                 $query->where('name', 'like', '%' . $search . '%');
        //             })
        //             ->orderBy('created_at', 'desc')
        //             ->paginate(15)
        //             ->withQueryString();
                    
        // return view('users.index', compact('users', 'search'));
    }
    

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
            // Validate incoming request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    DB::beginTransaction();
    info("User creation started");
    try {
        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Log it (This shows in terminal if you run `php artisan serve`)
        info("User created successfully: ID {$user->id}");

        // Commit the transaction
        DB::commit();

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    } catch (\Exception $e) {
        DB::rollBack();

        // Log error to terminal
        info("Transaction rolled back due to error: " . $e->getMessage());

        return back()->withErrors(['error' => 'Something went wrong.']);
    }

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
        /*Added today Role Field*/
        // if (!auth()->user()->hasRole('Admin')) {
        //     abort(403);
        // }
        
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:5|confirmed'
        ]);
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
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