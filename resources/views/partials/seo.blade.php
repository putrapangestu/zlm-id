@php
    $defaultTitle = 'ZLM.ID — Premium Laptop Store & Refurbished Bergaransi';
    $defaultDesc = 'ZLM.ID menyediakan pilihan laptop premium, gaming, dan kerja bergaransi dengan inspeksi Quality Control (QC) ketat. Transaksi aman online & kasir offline.';
    $defaultKeywords = 'laptop premium, laptop gaming, beli laptop, laptop refurbished bergaransi, thinkpad, macbook, rog, zlm id';
    $defaultImage = asset('assets/logo.png');

    $pageTitle = !empty(trim($__env->yieldContent('title'))) ? $__env->yieldContent('title') : ($seoTitle ?? $defaultTitle);
    $metaDescription = $seoDescription ?? $defaultDesc;
    $metaKeywords = $seoKeywords ?? $defaultKeywords;
    $metaImage = $seoImage ?? $defaultImage;
    $metaUrl = $seoUrl ?? url()->current();
    $metaType = $seoType ?? 'website';
@endphp

<!-- Primary Meta Tags -->
<title>{{ $pageTitle }}</title>
<meta name="title" content="{{ $pageTitle }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="robots" content="index, follow">
<meta name="language" content="Indonesian">
<meta name="author" content="{{ $seoAuthor ?? 'ZLM.ID' }}">
<link rel="canonical" href="{{ $metaUrl }}">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:type" content="{{ $metaType }}">
<meta property="og:site_name" content="ZLM.ID">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $metaUrl }}">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">
