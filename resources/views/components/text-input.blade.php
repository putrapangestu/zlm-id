@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 focus:border-[#DF5E1D] focus:ring-[#DF5E1D]/20 rounded-xl shadow-sm bg-gray-50 focus:bg-white transition']) }}>