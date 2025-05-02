{{-- File: resources/views/about.blade.php --}}
<x-app-layout>
    {{-- Define the content for the header slot (optional) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    {{-- Main content area --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                        Welcome to Our Application!
                    </h1>

                    <p class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed">
                        This is the about page for the application. Here you can describe what the
                        application does, its purpose, the team behind it, or any other relevant
                        information you want to share with your users.
                    </p>

                    <p class="mt-4 text-gray-500 dark:text-gray-400 leading-relaxed">
                        Feel free to replace this placeholder text with your actual content. You can add
                        more sections, images, and styling as needed using standard HTML and Tailwind CSS classes
                        provided by Jetstream.
                    </p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>