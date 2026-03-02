<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Biodata') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                        Data Diri Mahasiswa
                    </h1>

                    <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-3 font-semibold w-40">Nama</td>
                                <td class="py-3 px-2">:</td>
                                <td class="py-3">Arya Bagas Saputra</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-3 font-semibold">NIM</td>
                                <td class="py-3 px-2">:</td>
                                <td class="py-3">20230140029</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td class="py-3 font-semibold">Program Studi</td>
                                <td class="py-3 px-2">:</td>
                                <td class="py-3">Teknologi Informasi</td>
                            </tr>
                            <tr>
                                <td class="py-3 font-semibold">Hobi</td>
                                <td class="py-3 px-2">:</td>
                                <td class="py-3">Fotografi</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>