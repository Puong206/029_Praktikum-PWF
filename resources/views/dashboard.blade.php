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

            <!-- Menu Access Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Available Features</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">📊</span>
                                <h4 class="font-semibold text-blue-900 dark:text-blue-300">Dashboard</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View your dashboard</p>
                        </a>

                        <!-- Product Link (Available to all) -->
                        <a href="{{ route('product.index') }}" class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">📦</span>
                                <h4 class="font-semibold text-green-900 dark:text-green-300">Product</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage products</p>
                        </a>

                        <!-- Category Link (Only for Admin) -->
                        @can('manage-category')
                        <a href="{{ route('category.index') }}" class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">🏷️</span>
                                <h4 class="font-semibold text-purple-900 dark:text-purple-300">Category</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Manage categories</p>
                        </a>
                        @endcan

                        <!-- About Link -->
                        <a href="{{ route('about') }}" class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">ℹ️</span>
                                <h4 class="font-semibold text-yellow-900 dark:text-yellow-300">About</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Learn about us</p>
                        </a>

                        <!-- Profile Link -->
                        <a href="{{ route('profile.edit') }}" class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">👤</span>
                                <h4 class="font-semibold text-orange-900 dark:text-orange-300">Profile</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Edit your profile</p>
                        </a>

                        <!-- API Docs Link (Only for Admin) -->
                        @can('manage-category')
                        <a href="{{ route('docs.api.ui') }}" target="_blank" class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg hover:shadow-md transition">
                            <div class="flex items-center mb-2">
                                <span class="text-2xl mr-3">🔌</span>
                                <h4 class="font-semibold text-red-900 dark:text-red-300">API Docs</h4>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">View API documentation</p>
                        </a>
                        @endcan
                    </div>

                    <!-- Access Restriction Notice -->
                    @if(Auth::user()->role === 'user')
                    <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            <strong>ℹ️ Note:</strong> As a regular user, you have limited access. Contact an administrator to access Category management and API documentation.
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
