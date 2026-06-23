@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('heading', 'Pengaturan Toko')

@section('content')
<div x-data="{ tab: '{{ $tab }}' }" class="max-w-3xl">
    <!-- Tab Navigation -->
    <div class="flex gap-1 mb-8 bg-gray-100 p-1 rounded-lg w-fit">
        <button @click="tab = 'general'" :class="{ 'bg-white shadow-sm text-[#363230]': tab === 'general', 'text-gray-500 hover:text-gray-700': tab !== 'general' }" class="px-4 py-2 rounded-md text-sm font-medium cursor-pointer transition-all">
            General
        </button>
        <button @click="tab = 'social'" :class="{ 'bg-white shadow-sm text-[#363230]': tab === 'social', 'text-gray-500 hover:text-gray-700': tab !== 'social' }" class="px-4 py-2 rounded-md text-sm font-medium cursor-pointer transition-all">
            Sosial Media
        </button>
        <button @click="tab = 'location'" :class="{ 'bg-white shadow-sm text-[#363230]': tab === 'location', 'text-gray-500 hover:text-gray-700': tab !== 'location' }" class="px-4 py-2 rounded-md text-sm font-medium cursor-pointer transition-all">
            Lokasi
        </button>
    </div>

    <!-- Tab: General -->
    <form x-show="tab === 'general'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="general">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div>
                <label for="store_name" class="block text-sm font-medium text-gray-700 mb-1">Store Name <span class="text-red-500">*</span></label>
                <input type="text" name="store_name" id="store_name" value="{{ config('settings.store_name', 'ZLM.ID') }}" required
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm">
                @error('store_name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="store_description" id="store_description" rows="3"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Brief description about your store...">{{ config('settings.store_description', '') }}</textarea>
                @error('store_description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="store_email" id="store_email" value="{{ config('settings.store_email', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="support@zlm.id">
                @error('store_email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="store_phone" id="store_phone" value="{{ config('settings.store_phone', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="+62 123 4567 8910">
                @error('store_phone')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_opening_hours" class="block text-sm font-medium text-gray-700 mb-1">Opening Hours</label>
                <input type="text" name="store_opening_hours" id="store_opening_hours" value="{{ config('settings.store_opening_hours', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Sen - Sab: 09:00 - 18:00">
                @error('store_opening_hours')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                @if (config('settings.store_logo'))
                    <div class="mb-3">
                        <img src="{{ Storage::url(config('settings.store_logo')) }}" alt="Current logo" class="h-16 rounded-lg border border-gray-200">
                    </div>
                @endif
                <input type="file" name="store_logo" id="store_logo" accept="image/*"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, or WebP.</p>
                @error('store_logo')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">Save Settings</button>
        </div>
    </form>

    <!-- Tab: Social Media -->
    <form x-show="tab === 'social'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="social">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div>
                <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:instagram-linear" class="text-lg text-pink-500"></iconify-icon>
                        Instagram
                    </span>
                </label>
                <input type="url" name="social_instagram" id="social_instagram" value="{{ config('settings.social_instagram', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="https://instagram.com/youraccount">
                @error('social_instagram')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:facebook-linear" class="text-lg text-blue-600"></iconify-icon>
                        Facebook
                    </span>
                </label>
                <input type="url" name="social_facebook" id="social_facebook" value="{{ config('settings.social_facebook', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="https://facebook.com/yourpage">
                @error('social_facebook')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="social_tiktok" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:tiktok-linear" class="text-lg text-gray-800"></iconify-icon>
                        TikTok
                    </span>
                </label>
                <input type="url" name="social_tiktok" id="social_tiktok" value="{{ config('settings.social_tiktok', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="https://tiktok.com/@youraccount">
                @error('social_tiktok')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="social_youtube" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:youtube-linear" class="text-lg text-red-600"></iconify-icon>
                        YouTube
                    </span>
                </label>
                <input type="url" name="social_youtube" id="social_youtube" value="{{ config('settings.social_youtube', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="https://youtube.com/@yourchannel">
                @error('social_youtube')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:whatsapp-linear" class="text-lg text-green-500"></iconify-icon>
                        WhatsApp Number
                    </span>
                </label>
                <input type="text" name="store_whatsapp" id="store_whatsapp" value="{{ config('settings.store_whatsapp', '') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="6212345678910">
                <p class="text-xs text-gray-500 mt-1">Include country code without + sign.</p>
                @error('store_whatsapp')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">Save Settings</button>
        </div>
    </form>

    <!-- Tab: Location -->
    <form x-show="tab === 'location'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="location">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div>
                <label for="store_address" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar:map-point-linear" class="text-lg text-[#DF5E1D]"></iconify-icon>
                        Address
                    </span>
                </label>
                <textarea name="store_address" id="store_address" rows="3"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Jl. Raya Malang No. 123, Malang, Jawa Timur">{{ config('settings.store_address', '') }}</textarea>
                @error('store_address')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="store_google_maps" class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="flex items-center gap-2">
                        <iconify-icon icon="solar: globe-linear" class="text-lg text-blue-500"></iconify-icon>
                        Google Maps Embed URL
                    </span>
                </label>
                <textarea name="store_google_maps" id="store_google_maps" rows="3"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Paste Google Maps embed URL here...">{{ config('settings.store_google_maps', '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Go to Google Maps, click Share > Embed, and copy the src URL.</p>
                @error('store_google_maps')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">Save Settings</button>
        </div>
    </form>
</div>
@endsection
