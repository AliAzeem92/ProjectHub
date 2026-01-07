<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Projects -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Total Projects</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_projects'] }}</div>
                    </div>
                </div>

                <!-- Total Users (Admin Only) -->
                @if($stats['total_users'] !== null)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Total Users</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
                    </div>
                </div>
                @endif

                <!-- Total Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Total Tasks</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</div>
                    </div>
                </div>

                <!-- My Pending Tasks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-sm font-medium text-gray-500">Pending Tasks</div>
                        <div class="mt-2 text-3xl font-bold text-indigo-600">{{ $stats['my_pending_tasks'] }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome back, {{ auth()->user()->name }}!</h3>
                    <p class="text-gray-600">{{ __("You're logged in!") }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
