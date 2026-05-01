<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Role Display Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}! 👋</h3>
                            <p class="text-gray-600 dark:text-gray-400">You're logged in to your account</p>
                        </div>
                        <div class="bg-indigo-100 dark:bg-indigo-900 px-6 py-4 rounded-lg">
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">Your Role</p>
                            <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                {{ ucfirst(Auth::user()->role) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
