<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $project->name }}
            </h2>
            <div class="space-x-2">
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Edit Project
                    </a>
                @endcan
                <a href="{{ route('projects.index') }}" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Details</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Project Name</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Status</label>
                                    <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($project->status === 'completed') bg-green-100 text-green-800
                                        @elseif($project->status === 'in_progress') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Owner</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->user->name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Created</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->created_at->format('M d, Y') }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $project->updated_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @if($project->description)
                                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $project->description }}</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">No description provided.</p>
                                @endif
                            </div>

                            <!-- Tasks Section -->
                            <div class="mt-8">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Tasks</h3>
                                    @can('create', App\Models\Task::class) <!-- Changed from 'update' project to 'create' task check -->
                                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-3 rounded">
                                            Add Task
                                        </a>
                                    @endcan
                                </div>
                                <div class="bg-white border border-gray-200 rounded-lg">
                                    @if($project->tasks->count() > 0)
                                        <ul class="divide-y divide-gray-200">
                                            @foreach($project->tasks as $task)
                                                <li class="p-4 hover:bg-gray-50 flex justify-between items-center">
                                                    <div>
                                                        <a href="{{ route('tasks.show', $task) }}" class="font-medium text-indigo-600 hover:text-indigo-900">
                                                            {{ $task->title }}
                                                        </a>
                                                        <div class="text-sm text-gray-500">
                                                            Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }} | 
                                                            <span class="
                                                                @if($task->status === 'completed') text-green-600
                                                                @elseif($task->status === 'in_progress') text-yellow-600
                                                                @else text-gray-600 @endif
                                                            ">
                                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @can('update', $task)
                                                        <div class="flex space-x-2">
                                                            <a href="{{ route('tasks.edit', $task) }}" class="text-gray-400 hover:text-gray-600">
                                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>
                                                            </a>
                                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Delete task?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-400 hover:text-red-600">
                                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="p-4 text-center text-sm text-gray-500">No tasks found.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @can('delete', $project)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex justify-end">
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                        onclick="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                        Delete Project
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>