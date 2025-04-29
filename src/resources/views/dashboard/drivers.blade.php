<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Drivers') }}
            </h2>
            <a href="{{ route('dashboard.drivers.create') }}"
               class="inline-block px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-800 text-sm font-medium">
                + Add Driver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Search & Add Driver Row --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('dashboard.drivers') }}" class="w-full">
                    <div class="flex w-full rounded-md border border-gray-300 overflow-hidden bg-white dark:bg-gray-800">
                        {{-- Stretchy search input --}}
                        <input
                            type="text"
                            name="search"
                            placeholder="Search Drivers..."
                            value="{{ request('search') }}"
                            class="flex-1 min-w-0 px-4 py-2 bg-transparent
                                    text-gray-900 dark:text-white
                                    placeholder-gray-500 dark:placeholder-gray-400
                                    focus:outline-none"
                        />

                        {{-- Pinned search button --}}
                        <button
                            type="submit"
                            class="flex-none px-4 py-2 bg-gray-700 text-white hover:bg-gray-600
                                    dark:bg-gray-700 dark:hover:bg-gray-600 transition"
                            title="Search"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 103.5 3.5a7.5 7.5 0 0013.15 13.15z"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <hr>
            
            {{-- Driver Cards --}}
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($drivers as $driver)
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden flex flex-col">
                        <!-- Team color bar: ensure $driver->team is loaded and has color -->
                        <div class="h-2" style="background-color: {{ $driver->team->color ?? '#111827' }};"></div>
                        <div class="p-4 flex flex-col flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-2xl text-gray-900 dark:text-gray-100 font-bold">{{ $driver->id }}</span>
                                <span class="fi fi-{{ strtolower($driver->nationality) }} fis mr-2"></span>
                            </div>
                            <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $driver->name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                {{ $driver->team->name ?? '' }}
                            </p>
                            <div class="mt-auto flex space-x-2">
                                <a href="{{ route('dashboard.drivers.edit', $driver) }}"
                                   class="flex-1 inline-block px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 text-center">
                                    Edit
                                </a>
                                <form action="{{ route('dashboard.drivers.destroy', $driver) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Retire this Driver?')"
                                            class="w-full px-3 py-2 text-sm font-medium text-red-600 border border-red-600 rounded hover:bg-red-600 hover:text-white transition">
                                        Retire
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>