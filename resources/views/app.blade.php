<!DOCTYPE html>
<html class="scroll-smooth" lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php($seo = $seo ?? \App\Support\Seo::home())
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <meta name="author" content="libfree">
    <meta name="theme-color" content="#F4B942">

    @if (! empty($seo['canonical']))
        <link rel="canonical" href="{{ $seo['canonical'] }}">
    @endif

    <meta property="og:type" content="{{ $seo['og']['type'] }}">
    <meta property="og:site_name" content="{{ $seo['og']['site_name'] }}">
    <meta property="og:locale" content="{{ $seo['og']['locale'] }}">
    <meta property="og:title" content="{{ $seo['og']['title'] }}">
    <meta property="og:description" content="{{ $seo['og']['description'] }}">
    @if (! empty($seo['og']['url']))
        <meta property="og:url" content="{{ $seo['og']['url'] }}">
    @endif
    @if (! empty($seo['og']['image']))
        <meta property="og:image" content="{{ $seo['og']['image'] }}">
    @endif

    <meta name="twitter:card" content="{{ $seo['twitter']['card'] }}">
    <meta name="twitter:title" content="{{ $seo['twitter']['title'] }}">
    <meta name="twitter:description" content="{{ $seo['twitter']['description'] }}">
    @if (! empty($seo['twitter']['image']))
        <meta name="twitter:image" content="{{ $seo['twitter']['image'] }}">
    @endif

    @foreach (($seo['json_ld'] ?? []) as $schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endforeach

    <!-- Google Fonts dimuat dari resources/css/app.css (layer `fonts`) agar utility Tailwind menang -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-cream text-brand-dark antialiased bg-rings relative selection:bg-brand-yellow selection:text-brand-dark">
    <div id="app"></div>
</body>

</html>
