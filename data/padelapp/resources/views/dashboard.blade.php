<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Welcome back, " . Auth::user()->name . "!") }}
                </div>
            </div>

            {{-- Opciones para administrador --}}
            @if(Auth::user()->rol === 'admin')
                <div class="mt-4 bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="font-semibold text-lg">{{ __('Admin Panel') }}</h3>
                        <ul class="list-disc list-inside">
                            <li><a href="{{ route('admin.manage-tracks') }}" class="text-blue-500 hover:underline">{{ __('Manage Tracks') }}</a></li>
                            <li><a href="{{ route('admin.settings') }}" class="text-blue-500 hover:underline">{{ __('Settings') }}</a></li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
