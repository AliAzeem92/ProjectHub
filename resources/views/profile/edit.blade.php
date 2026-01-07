<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Account Role') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Your current account role and permissions.') }}
                            </p>
                        </header>

                        <div class="mt-6">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <div class="mt-1">
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                            @if(auth()->user()->role === 'admin') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700">Permissions</label>
                                    <div class="mt-1 text-sm text-gray-600">
                                        @if(auth()->user()->role === 'admin')
                                            Can create, edit, delete all projects
                                        @else
                                            Can only update status of assigned projects
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
