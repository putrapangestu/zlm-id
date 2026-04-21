<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 md:p-12 text-white mb-12">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Welcome to Your Laravel App
                </h1>
                <p class="text-xl mb-6 opacity-90">
                    Powered by Tailwind CSS for rapid UI development
                </p>
                <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Get Started
                </button>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Lightning Fast
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Built with performance in mind for the best user experience.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-green-500 text-white mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Responsive Design
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Looks great on all devices with Tailwind's responsive utilities.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-purple-500 text-white mb-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Fully Customizable
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Tailor every aspect to match your brand identity perfectly.
                    </p>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-indigo-500 to-pink-500 rounded-lg shadow-lg p-8 text-white text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to build something amazing?</h2>
                <p class="text-lg mb-6 opacity-90">
                    Start developing with Laravel and Tailwind CSS today
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Learn More
                    </button>
                    <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                        Documentation
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
