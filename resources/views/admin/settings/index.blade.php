@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-[#363230] mb-6">Settings</h1>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white p-6 rounded-xl border border-gray-200">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[#363230] mb-1">Tax Rate (%)</label>
                <input type="number" name="tax_rate"
                       value="{{ config('settings.tax_rate', 11) }}"
                       min="0" max="100" step="0.1" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                <p class="text-xs text-gray-500 mt-1">Default: 11%. Applied to all orders.</p>
                @error('tax_rate')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="pt-4 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-4">Bank Information (for Manual Transfer)</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-1">Bank Name</label>
                        <input type="text" value="{{ config('settings.bank_name', 'BCA') }}" disabled
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-1">Account Number</label>
                        <input type="text" value="{{ config('settings.bank_account', '123-456-7890') }}" disabled
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#363230] mb-1">Account Holder</label>
                        <input type="text" value="{{ config('settings.bank_holder', 'PT ZLM ID') }}" disabled
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2.5 bg-[#DF5E1D] text-white rounded-xl hover:bg-[#c94f14] transition-colors text-sm font-medium">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
