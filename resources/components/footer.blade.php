<footer class="bg-gray-800 text-gray-100 mt-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'Laravel') }}</h3>
                <p class="text-gray-400">Build amazing things with Laravel and Tailwind CSS.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Documentation</a></li>
                    <li><a href="#" class="hover:text-white transition">Support</a></li>
                    <li><a href="#" class="hover:text-white transition">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition">Twitter</a></li>
                    <li><a href="#" class="hover:text-white transition">GitHub</a></li>
                    <li><a href="#" class="hover:text-white transition">Discord</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
        </div>
    </div>
</footer>
