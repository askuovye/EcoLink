<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Points') }}
        </h2>
    </x-slot>
    <form action="{{ route('points.update', $point->id )}}" method="POST">
        @csrf
        @method('PUT')
        
        <a href="{{ route('points.create') }}" class="bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded mb-4">Novo Ponto de Coleta</a>

        <div class="p-6 bg-white dark:bg-gray-800">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                <input type="text" name="name" id="name" value="{{ $point->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                <input type="text" name="address" id="address" value="{{ $point->address }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" required>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                <input type="text" name="type" id="type" value="{{ $point->type }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring focus:ring-green-500 focus:ring-opacity-50" required>
            </div>
            <div class="flex items-center justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                    {{ __('Update') }}
                </button>
            </div>
        </div>
    </form>
</x-app-layout>