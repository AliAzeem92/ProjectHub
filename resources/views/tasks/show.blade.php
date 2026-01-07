<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('projects.show', $task->project_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between">
                        <h3 class="text-2xl font-bold mb-4">{{ $task->title }}</h3>
                        <div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold
                                @if($task->status === 'completed') bg-green-100 text-green-800
                                @elseif($task->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-6 text-gray-600">
                        Project: <span class="font-semibold text-gray-900">{{ $task->project->name }}</span>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900">Description</h4>
                        <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $task->description ?? 'No description.' }}</p>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-medium text-gray-900">Details</h4>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-gray-500">Due Date:</span>
                                <span class="ml-2 font-medium">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Created At:</span>
                                <span class="ml-2 font-medium">{{ $task->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    @can('update', $task)
                        <div class="border-t pt-6 flex justify-end space-x-4">
                            <a href="{{ route('tasks.edit', $task) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Edit Task
                            </a>
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete Task
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
