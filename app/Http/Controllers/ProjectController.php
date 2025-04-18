<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\User;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::withCount('tasks')->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load('tasks', 'users');
        $availableUsers = User::whereDoesntHave('projects', function($query) use ($project) {
            $query->where('project_id', $project->id);
        })->get();

        return view('projects.show', compact('project', 'availableUsers'));
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
    // new part task - 3
    public function assignUser(Request $request, Project $project)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
    ]);

    $project->users()->attach($validated['user_id']);

    return response()->json([
        'status' => 'success',
        'message' => 'User assigned to project successfully.'
    ]);
}

public function removeUser(Project $project, User $user)
{
    $project->users()->detach($user->id);

    return response()->json([
        'status' => 'success',
        'message' => 'User removed from project successfully.'
    ]);
}
}