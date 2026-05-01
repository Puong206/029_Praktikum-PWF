<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <p class="mt-1 text-lg">{{ $product->name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qty</label>
                        <p class="mt-1 text-lg">{{ $product->qty }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price</label>
                        <p class="mt-1 text-lg">{{ $product->price }}</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Owner</label>
                        <p class="mt-1 text-lg">{{ $product->user->name ?? 'Unknown' }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('product.index') }}"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-semibold">
                            &larr; Back to List
                        </a>

                        @can('manage-product')
                            <x-edit-product :url="route('product.edit', $product)" />
                            <x-delete-product :url="route('product.destroy', $product)" />
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>