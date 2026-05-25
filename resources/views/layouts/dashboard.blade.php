<html lang="en" class="antialiased scroll-smooth"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZLM.ID - Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(1rem); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
</head>
<body class="font-sans bg-[#FAFAFA] text-[#363230] selection:bg-[#DF5E1D]/20 selection:text-[#DF5E1D] overflow-hidden">

<div class="flex h-screen w-full">

    @include('components.dashboard-sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        @include('components.dashboard-navbar')

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

        @include('components.dashboard-footer')

    </div>
</div>

@stack('scripts')

</body></html>
