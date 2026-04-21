<footer class="bg-dark text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-semibold text-white mb-4">LaptopHub</h3>
                <p class="text-sm">Your trusted source for premium laptops and computing devices.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('landing.home') }}" class="hover:text-primary transition">Home</a></li>
                    <li><a href="{{ route('landing.search') }}" class="hover:text-primary transition">Products</a></li>
                    <li><a href="#" class="hover:text-primary transition">About Us</a></li>
                    <li><a href="#" class="hover:text-primary transition">Contact</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Support</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-primary transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-primary transition">Track Order</a></li>
                    <li><a href="#" class="hover:text-primary transition">Returns</a></li>
                    <li><a href="#" class="hover:text-primary transition">FAQ</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-lg font-semibold text-white mb-4">Contact Us</h4>
                <p class="text-sm mb-2">📧 support@laptoHub.com</p>
                <p class="text-sm mb-2">📱 +1 (555) 123-4567</p>
                <p class="text-sm">📍 123 Tech Street, Silicon Valley</p>
            </div>
        </div>

        <div class="border-t border-gray-700 pt-8 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} LaptopHub. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-primary transition">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-primary transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</footer>
