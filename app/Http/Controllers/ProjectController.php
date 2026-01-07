<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Project::class);

        $projects = auth()->user()->role === 'admin'
            ? Project::all()
            : auth()->user()->projects;

        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        $users = [];
        if (auth()->user()->role === 'admin') {
            $users = User::where('role', 'user')->get();
    }

    return view('projects.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $isAdmin = auth()->user()->role === 'admin';

        $data = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:pending,in_progress,completed',
        'user_id' => $isAdmin ? 'required|exists:users,id' : 'nullable',
    ]);

    if (!$isAdmin) {
        $data['user_id'] = auth()->id();
    }

    Project::create($data);

    return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $users = User::where('role', 'user')->get();

        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        // If user is not admin, only allow status updates
        if (auth()->user()->role !== 'admin') {
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);

            $project->update([
                'status' => $request->status,
            ]);
        } else {
            // Admin can update everything
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,in_progress,completed',
                'user_id' => 'required|exists:users,id',
            ]);

            $project->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'user_id' => $request->user_id,
            ]);
        }

        return redirect()->route('projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index');
    }
}
