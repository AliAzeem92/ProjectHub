<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * Use this mainly if we want to show "My Tasks" across all projects.
     */
    public function index()
    {
        $tasks = auth()->user()->role === 'admin' 
            ? Task::with('project')->get()
            : Task::whereHas('project', function($query) {
                $query->where('user_id', auth()->id());
            })->with('project')->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     * We need a project_id to know where to attach it.
     */
    public function create(Request $request)
    {
        $projects = auth()->user()->role === 'admin'
            ? Project::all()
            : auth()->user()->projects;

        $selectedProject = $request->query('project_id');

        return view('tasks.create', compact('projects', 'selectedProject'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'due_date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
        ]);

        $project = Project::findOrFail($request->project_id);
        $this->authorize('update', $project);

        Task::create($request->all());

        return redirect()->route('projects.show', $project);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        if (auth()->user()->role !== 'admin') {
            // Non-admins can strictly only update status
            $validated = $request->validate([
                'status' => 'required|in:pending,in_progress,completed',
            ]);
            
            $task->update(['status' => $validated['status']]);
        } else {
            // Admins can update everything
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,in_progress,completed',
                'due_date' => 'required|date',
            ]);
            
            $task->update($validated);
        }

        return redirect()->route('projects.show', $task->project_id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        
        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.show', $projectId);
    }
}