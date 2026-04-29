<html lang="en" class="scroll-smooth"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ZLM.ID - Toko Laptop Bekas Berkualitas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        iconify-icon {
            stroke-width: 1.5;
        }

        /* Preloader Styles */
        .preloader-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        }

        .preloader-wrapper.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .preloader-content {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
        }

        .preloader-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #DF5E1D 0%, #c45218 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse-scale 2s ease-in-out infinite;
        }

        .preloader-logo img {
            width: 60px;
            height: 60px;
            object-fit: contain;
            filter: brightness(0) invert(1);
        }

        .preloader-text {
            font-size: 28px;
            font-weight: 600;
            color: #363230;
            letter-spacing: -0.5px;
            animation: fade-in-out 2s ease-in-out infinite;
        }

        .preloader-line {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #DF5E1D 0%, transparent 100%);
            border-radius: 2px;
            animation: expand-width 1.5s ease-in-out infinite;
        }

        @keyframes pulse-scale {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes fade-in-out {
            0%, 100% {
                opacity: 0.6;
            }
            50% {
                opacity: 1;
            }
        }

        @keyframes expand-width {
            0%, 100% {
                width: 40px;
            }
            50% {
                width: 80px;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-800 selection:bg-[#DF5E1D] selection:text-white">

    <!-- Preloader -->
    <div class="preloader-wrapper" id="preloader">
        <div class="preloader-content">
            <div class="preloader-logo">
                <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID">
            </div>
            <div class="preloader-text">ZLM.ID</div>
            <div class="preloader-line"></div>
        </div>
    </div>

    @include('components.landing-nav')

    @yield('content')

    @include('components.landing-footer')

    <!-- Preloader Script -->
    <script>
        // Hide preloader when page is fully loaded
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }
        });

        // Also hide preloader if it takes too long (safety timeout)
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            if (preloader) {
                preloader.classList.add('hidden');
            }
        }, 3000);
    </script>

    @stack('scripts')

</body></html>
