<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20 pb-28">
<!-- BEGIN: HeroSection -->
<!-- Hero section with search & workout-inspired registration card -->
<section class="pt-4 lg:pt-8" data-purpose="hero-section" id="hero" @click="onHeroClick">
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
<!-- Left Column: Copy & Search -->
<div class="lg:col-span-7 space-y-6">
<!-- Badge -->
<div class="inline-flex items-center gap-2 neo-pill bg-brand-yellow-light px-4 py-1.5 text-xs sm:text-sm text-brand-dark">
<span class="w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark animate-pulse"></span>
<span class="font-bold">📚 100% Terbuka &amp; Bebas Akses Tanpa Biaya</span>
</div>
<!-- Headline -->
<h1 class="font-display font-extrabold text-4xl sm:text-5xl lg:text-6xl text-brand-dark tracking-tight leading-[1.15] sm:leading-none">
            Baca buku gratis, <br>
<span class="relative inline-block">
              kapan saja,
              <span class="absolute left-0 bottom-1 w-full h-3 bg-brand-yellow/60 -z-10 rounded"></span>
</span> di mana saja.
          </h1>
<!-- Subtitle -->
<p class="text-base sm:text-lg text-brand-muted max-w-xl leading-relaxed sm:leading-7 font-medium">
            Akses puluhan ribu buku sastra nusantara, ensiklopedia sains, arsip sejarah, dan dongeng anak dalam genggaman tanpa batasan kuota dan tanpa biaya langganan sepeserpun.
          </p>
<!-- Search Bar Container -->
<div class="pt-2 max-w-xl">
<form action="#katalog" class="neo-card p-2 sm:p-2.5 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-white" method="GET">
<div class="flex items-center gap-2.5 flex-1 px-3">
<svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<input ref="searchInput" aria-label="Pencarian buku" class="w-full border-0 p-0 text-sm sm:text-base font-medium placeholder:text-gray-400 focus:ring-0 text-brand-dark bg-transparent" placeholder="Cari judul buku, penulis, topik naskah..." type="text">
</div>
<button class="neo-pill bg-brand-yellow hover:bg-[#eab039] text-brand-dark text-sm px-6 py-3 whitespace-nowrap" type="submit">
                Cari Buku 🔍
              </button>
</form>
<!-- Popular Search Pills -->
<div class="flex flex-wrap items-center gap-2 mt-4 text-xs font-semibold">
<span class="text-brand-muted">Pencarian Populer:</span>
<a class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" href="#katalog">Novel Pramoedya</a>
<a class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" href="#katalog">Sejarah Nusantara</a>
<a class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" href="#katalog">Sains Populer</a>
<a class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" href="#katalog">Filsafat</a>
<a class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" href="#katalog">Dongeng Anak</a>
</div>
</div>
</div>
<!-- Right Column: Card archetyped from reference image workout container -->
<div class="lg:col-span-5 flex justify-center">
<div class="w-full max-w-sm sm:max-w-md">
<!-- Main Archetype Card (Stacked top white + bottom green container) -->
<div class="rounded-3xl border-2 border-brand-dark bg-brand-green/20 p-3 shadow-solid-lg">
  <div class="neo-card p-5 sm:p-6 bg-white relative space-y-4">
    <!-- Header bar with badge and slide buttons -->
    <div class="flex items-center justify-between gap-2">
      <div class="inline-flex items-center gap-1.5 neo-pill bg-brand-yellow-light px-3 py-1 text-xs text-brand-dark">
        <span class="w-2 h-2 rounded-full bg-brand-coral animate-pulse"></span>
        <span class="font-bold text-[11px]">🔥 Sedang Populer</span>
      </div>
      <div class="flex items-center gap-1.5">
        <button type="button" id="prevHeroBook" aria-label="Buku sebelumnya" class="w-7 h-7 rounded-full border-1.5 border-brand-dark bg-white hover:bg-gray-100 flex items-center justify-center text-xs font-bold transition-transform active:scale-95">←</button>
        <button type="button" id="nextHeroBook" aria-label="Buku selanjutnya" class="w-7 h-7 rounded-full border-1.5 border-brand-dark bg-brand-yellow hover:bg-[#ebb035] flex items-center justify-center text-xs font-bold transition-transform active:scale-95">→</button>
      </div>
    </div>

    <!-- Main Book Cover Display Area -->
    <div class="relative overflow-hidden rounded-2xl border-2 border-brand-dark bg-brand-cream p-4">
      <!-- Slides Container -->
      <div id="heroBookSlides" class="relative min-h-[220px]">
        <!-- Slide 1: Bumi Manusia -->
        <div class="hero-book-slide transition-all duration-500 block opacity-100" data-book-index="0">
          <div class="flex gap-4 items-center">
            <div class="w-28 h-40 shrink-0 rounded-xl border-2 border-brand-dark bg-[#9C3826] p-3 flex flex-col justify-between text-white shadow-solid relative overflow-hidden group">
              <div class="flex justify-between items-start">
                <span class="text-[9px] font-mono tracking-widest uppercase bg-black/40 px-1.5 py-0.5 rounded border border-white/20">Klasik</span>
                <span class="text-base">🖋️</span>
              </div>
              <div>
                <h4 class="font-display font-black text-sm leading-tight text-white">BUMI MANUSIA</h4>
                <p class="text-[10px] text-white/80 font-sans mt-0.5">Pramoedya A. T.</p>
              </div>
              <div class="text-[8px] text-white/60 tracking-wider truncate">Tetralogi Buru #1</div>
            </div>
            <div class="flex-1 space-y-2 text-left">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span class="neo-pill bg-brand-coral-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Sastra Sejarah</span>
                <span class="neo-pill bg-brand-green-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Domain Publik</span>
              </div>
              <h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-snug sm:leading-7">Bumi Manusia</h3>
              <p class="text-[11px] text-brand-muted font-medium line-clamp-2 leading-relaxed">Kisah Minke di era pergulatan kolonial Hindia Belanda yang membangkitkan kesadaran nasional.</p>
              <div class="text-[11px] font-bold text-brand-dark flex items-center gap-2 pt-0.5">
                <span>⭐ 4.9 (12.4k)</span>
                <span>•</span>
                <span>535 Hlm</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 2: Kapal Van der Wijck -->
        <div class="hero-book-slide transition-all duration-500 hidden opacity-0" data-book-index="1">
          <div class="flex gap-4 items-center">
            <div class="w-28 h-40 shrink-0 rounded-xl border-2 border-brand-dark bg-[#2C3E50] p-3 flex flex-col justify-between text-white shadow-solid relative overflow-hidden group">
              <div class="flex justify-between items-start">
                <span class="text-[9px] font-mono tracking-widest uppercase bg-black/40 px-1.5 py-0.5 rounded border border-white/20">Roman</span>
                <span class="text-base">🚢</span>
              </div>
              <div>
                <h4 class="font-display font-black text-sm leading-tight text-white">VAN DER WIJCK</h4>
                <p class="text-[10px] text-white/80 font-sans mt-0.5">Buya Hamka</p>
              </div>
              <div class="text-[8px] text-white/60 tracking-wider truncate">Balai Pustaka 1938</div>
            </div>
            <div class="flex-1 space-y-2 text-left">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span class="neo-pill bg-brand-blue-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Romansa Klasik</span>
                <span class="neo-pill bg-brand-green-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Domain Publik</span>
              </div>
              <h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-snug sm:leading-7">Kapal Van der Wijck</h3>
              <p class="text-[11px] text-brand-muted font-medium line-clamp-2 leading-relaxed">Tragedi cinta Zainuddin dan Hayati yang terhalang adat istiadat Minangkabau yang kaku.</p>
              <div class="text-[11px] font-bold text-brand-dark flex items-center gap-2 pt-0.5">
                <span>⭐ 4.8 (9.8k)</span>
                <span>•</span>
                <span>224 Hlm</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 3: Gadis Kretek -->
        <div class="hero-book-slide transition-all duration-500 hidden opacity-0" data-book-index="2">
          <div class="flex gap-4 items-center">
            <div class="w-28 h-40 shrink-0 rounded-xl border-2 border-brand-dark bg-[#2D4059] p-3 flex flex-col justify-between text-white shadow-solid relative overflow-hidden group">
              <div class="flex justify-between items-start">
                <span class="text-[9px] font-mono tracking-widest uppercase bg-black/40 px-1.5 py-0.5 rounded border border-white/20">Fiksi</span>
                <span class="text-base">🍂</span>
              </div>
              <div>
                <h4 class="font-display font-black text-sm leading-tight text-white">GADIS KRETEK</h4>
                <p class="text-[10px] text-white/80 font-sans mt-0.5">Ratih Kumala</p>
              </div>
              <div class="text-[8px] text-white/60 tracking-wider truncate">Rempah &amp; Asmara</div>
            </div>
            <div class="flex-1 space-y-2 text-left">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span class="neo-pill bg-brand-yellow-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Fiksi Sejarah</span>
                <span class="neo-pill bg-brand-peach px-2 py-0.5 text-[10px] text-brand-dark font-bold">Best Seller</span>
              </div>
              <h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-snug sm:leading-7">Gadis Kretek</h3>
              <p class="text-[11px] text-brand-muted font-medium line-clamp-2 leading-relaxed">Penelusuran rahasia keluarga pemilik pabrik kretek terbesar di Kota M dan cinta terlarang Jeng Yah.</p>
              <div class="text-[11px] font-bold text-brand-dark flex items-center gap-2 pt-0.5">
                <span>⭐ 4.8 (8.9k)</span>
                <span>•</span>
                <span>318 Hlm</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Slide 4: Madilog -->
        <div class="hero-book-slide transition-all duration-500 hidden opacity-0" data-book-index="3">
          <div class="flex gap-4 items-center">
            <div class="w-28 h-40 shrink-0 rounded-xl border-2 border-brand-dark bg-[#903749] p-3 flex flex-col justify-between text-white shadow-solid relative overflow-hidden group">
              <div class="flex justify-between items-start">
                <span class="text-[9px] font-mono tracking-widest uppercase bg-black/40 px-1.5 py-0.5 rounded border border-white/20">Filsafat</span>
                <span class="text-base">💡</span>
              </div>
              <div>
                <h4 class="font-display font-black text-sm leading-tight text-white">MADILOG</h4>
                <p class="text-[10px] text-white/80 font-sans mt-0.5">Tan Malaka</p>
              </div>
              <div class="text-[8px] text-white/60 tracking-wider truncate">Rasional Nusantara</div>
            </div>
            <div class="flex-1 space-y-2 text-left">
              <div class="flex items-center gap-1.5 flex-wrap">
                <span class="neo-pill bg-brand-lavender px-2 py-0.5 text-[10px] text-brand-dark font-bold">Filsafat Bangsa</span>
                <span class="neo-pill bg-brand-green-light px-2 py-0.5 text-[10px] text-brand-dark font-bold">Domain Publik</span>
              </div>
              <h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-snug sm:leading-7">Madilog</h3>
              <p class="text-[11px] text-brand-muted font-medium line-clamp-2 leading-relaxed">Mahakarya pemikiran rasional bangsa membongkar logika mistika menuju peradaban merdeka.</p>
              <div class="text-[11px] font-bold text-brand-dark flex items-center gap-2 pt-0.5">
                <span>⭐ 4.7 (6.3k)</span>
                <span>•</span>
                <span>568 Hlm</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Indicator dots & interactive preview thumbnails -->
    <div class="flex items-center justify-between gap-2 pt-1">
      <div class="flex items-center gap-1.5">
        <button type="button" class="hero-book-dot w-6 h-2 rounded-full border border-brand-dark bg-brand-yellow transition-all" data-dot-index="0" aria-label="Buku 1"></button>
        <button type="button" class="hero-book-dot w-2 h-2 rounded-full border border-brand-dark bg-gray-200 transition-all" data-dot-index="1" aria-label="Buku 2"></button>
        <button type="button" class="hero-book-dot w-2 h-2 rounded-full border border-brand-dark bg-gray-200 transition-all" data-dot-index="2" aria-label="Buku 3"></button>
        <button type="button" class="hero-book-dot w-2 h-2 rounded-full border border-brand-dark bg-gray-200 transition-all" data-dot-index="3" aria-label="Buku 4"></button>
      </div>
      <span class="text-[11px] font-bold text-brand-muted" id="heroBookCounter">1 dari 4 Buku</span>
    </div>

    <!-- Action Button -->
    <div class="pt-1">
      <a class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark px-6 py-2.5 text-sm w-full justify-center shadow-sm font-bold" href="#katalog">
        Lihat Buku Ini →
      </a>
    </div>
  </div>

  <!-- Bottom quick stats bar with pill cards -->
  <div class="pt-2.5 pb-0.5 px-2 grid grid-cols-2 gap-2 text-center text-xs font-bold">
    <div class="neo-pill bg-white py-1.5 px-3 flex items-center justify-center gap-1.5 text-[11px]">
      <span>📖</span>
      <span>Gratis Tanpa Kuota</span>
    </div>
    <div class="neo-pill bg-white py-1.5 px-3 flex items-center justify-center gap-1.5 text-[11px]">
      <span>⚡</span>
      <span>Format EPUB &amp; PDF</span>
    </div>
  </div>
</div>
</div>
</div>
</div>
</section>
<!-- END: HeroSection -->
<!-- BEGIN: ReadingHabitDashboard -->
<!-- Showcase based directly on reference components (Sleep details, Steps, Date strip, Durasi) -->

<!-- END: ReadingHabitDashboard -->
<!-- BEGIN: ContinueReadingAndDevices -->
<!-- Replicating the OnePlus Watch card and connected reader style -->

<!-- END: ContinueReadingAndDevices -->
<!-- BEGIN: FeaturedBooksSection -->
<!-- Featured Books Showcase Carousel -->
<section class="space-y-6" data-purpose="featured-books" id="katalog">
<div class="flex items-center justify-between">
<div>
<span class="neo-pill bg-brand-yellow px-3 py-1 text-xs font-bold text-brand-dark mb-1">
            ⭐ Kurasi Mingguan
          </span>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight">
            Buku Pilihan Redaksi
          </h2>
</div>
<!-- Navigation buttons with neo outline -->
<div class="flex items-center gap-2">
<button aria-label="Sebelumnya" class="w-10 h-10 rounded-full border-2 border-brand-dark bg-white hover:bg-gray-100 flex items-center justify-center font-bold">
            ←
          </button>
<button aria-label="Selanjutnya" class="w-10 h-10 rounded-full border-2 border-brand-dark bg-brand-yellow hover:bg-[#ebae34] flex items-center justify-center font-bold">
            →
          </button>
</div>
</div>
<!-- 4 Rich Featured Books Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Book Card 1 -->
<div class="neo-card p-5 flex flex-col justify-between hover:-translate-y-1 group bg-white">
<div>
<!-- Book Cover Art Box -->
<div class="w-full h-48 rounded-xl border-2 border-brand-dark bg-[#9C3826] p-4 flex flex-col justify-between text-white relative shadow-sm overflow-hidden mb-4">
<div class="flex justify-between items-start">
<span class="text-[10px] font-mono tracking-widest uppercase bg-black/40 px-2 py-0.5 rounded border border-white/20">Klasik</span>
<span class="text-xl">🖋️</span>
</div>
<div>
<h4 class="font-display font-black text-xl leading-tight text-white">BUMI MANUSIA</h4>
<p class="text-xs text-white/80 font-sans mt-0.5">Pramoedya Ananta Toer</p>
</div>
<div class="text-[9px] text-white/60 tracking-wider">Tetralogi Buru — Buku ke-1</div>
</div>
<!-- Details -->
<div class="flex items-center justify-between mb-2">
<span class="neo-pill bg-brand-coral-light px-2.5 py-0.5 text-[11px] text-brand-dark font-bold">Sastra Sejarah</span>
<span class="text-xs font-bold flex items-center gap-1 text-brand-dark">⭐ 4.9 <span class="text-brand-muted text-[10px]">(12.4k)</span></span>
</div>
<p class="text-xs text-brand-muted line-clamp-2 font-medium">
              Kisah Minke di era pergulatan kolonial Hindia Belanda yang membangkitkan kesadaran nasional.
            </p>
</div>
<div class="mt-4 pt-3 border-t-2 border-brand-dark/10">
<RouterLink class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs w-full py-2.5 justify-center" to="/buku/bumi-manusia">
              Baca Sekarang →
            </RouterLink>
</div>
</div>
<!-- Book Card 2 -->
<div class="neo-card p-5 flex flex-col justify-between hover:-translate-y-1 group bg-white">
<div>
<!-- Book Cover Art Box -->
<div class="w-full h-48 rounded-xl border-2 border-brand-dark bg-[#2D4059] p-4 flex flex-col justify-between text-white relative shadow-sm overflow-hidden mb-4">
<div class="flex justify-between items-start">
<span class="text-[10px] font-mono tracking-widest uppercase bg-black/40 px-2 py-0.5 rounded border border-white/20">Fiksi</span>
<span class="text-xl">🍂</span>
</div>
<div>
<h4 class="font-display font-black text-xl leading-tight text-white">GADIS KRETEK</h4>
<p class="text-xs text-white/80 font-sans mt-0.5">Ratih Kumala</p>
</div>
<div class="text-[9px] text-white/60 tracking-wider">Kisah Rempah &amp; Asmara</div>
</div>
<!-- Details -->
<div class="flex items-center justify-between mb-2">
<span class="neo-pill bg-brand-yellow-light px-2.5 py-0.5 text-[11px] text-brand-dark font-bold">Fiksi Sejarah</span>
<span class="text-xs font-bold flex items-center gap-1 text-brand-dark">⭐ 4.8 <span class="text-brand-muted text-[10px]">(8.9k)</span></span>
</div>
<p class="text-xs text-brand-muted line-clamp-2 font-medium">
              Penelusuran rahasia keluarga pemilik pabrik kretek terbesar di Kota M dan cinta terlarang Jeng Yah.
            </p>
</div>
<div class="mt-4 pt-3 border-t-2 border-brand-dark/10">
<a class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs w-full py-2.5 justify-center" href="#baca">
              Baca Sekarang →
            </a>
</div>
</div>
<!-- Book Card 3 -->
<div class="neo-card p-5 flex flex-col justify-between hover:-translate-y-1 group bg-white">
<div>
<!-- Book Cover Art Box -->
<div class="w-full h-48 rounded-xl border-2 border-brand-dark bg-[#285943] p-4 flex flex-col justify-between text-white relative shadow-sm overflow-hidden mb-4">
<div class="flex justify-between items-start">
<span class="text-[10px] font-mono tracking-widest uppercase bg-black/40 px-2 py-0.5 rounded border border-white/20">Stoikisme</span>
<span class="text-xl">🏛️</span>
</div>
<div>
<h4 class="font-display font-black text-xl leading-tight text-white">FILOSOFI TERAS</h4>
<p class="text-xs text-white/80 font-sans mt-0.5">Henry Manampiring</p>
</div>
<div class="text-[9px] text-white/60 tracking-wider">Filsafat Kuno Emosi Positif</div>
</div>
<!-- Details -->
<div class="flex items-center justify-between mb-2">
<span class="neo-pill bg-brand-green-light px-2.5 py-0.5 text-[11px] text-brand-dark font-bold">Pengembangan Diri</span>
<span class="text-xs font-bold flex items-center gap-1 text-brand-dark">⭐ 4.9 <span class="text-brand-muted text-[10px]">(15.1k)</span></span>
</div>
<p class="text-xs text-brand-muted line-clamp-2 font-medium">
              Panduan mental tangguh menghadapi kekhawatiran dan ketidakpastian hidup modern ala Stoisisme.
            </p>
</div>
<div class="mt-4 pt-3 border-t-2 border-brand-dark/10">
<a class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs w-full py-2.5 justify-center" href="#baca">
              Baca Sekarang →
            </a>
</div>
</div>
<!-- Book Card 4 -->
<div class="neo-card p-5 flex flex-col justify-between hover:-translate-y-1 group bg-white">
<div>
<!-- Book Cover Art Box -->
<div class="w-full h-48 rounded-xl border-2 border-brand-dark bg-[#903749] p-4 flex flex-col justify-between text-white relative shadow-sm overflow-hidden mb-4">
<div class="flex justify-between items-start">
<span class="text-[10px] font-mono tracking-widest uppercase bg-black/40 px-2 py-0.5 rounded border border-white/20">Filsafat</span>
<span class="text-xl">💡</span>
</div>
<div>
<h4 class="font-display font-black text-xl leading-tight text-white">MADILOG</h4>
<p class="text-xs text-white/80 font-sans mt-0.5">Tan Malaka</p>
</div>
<div class="text-[9px] text-white/60 tracking-wider">Materialisme Dialektika Logika</div>
</div>
<!-- Details -->
<div class="flex items-center justify-between mb-2">
<span class="neo-pill bg-brand-blue-light px-2.5 py-0.5 text-[11px] text-brand-dark font-bold">Filsafat &amp; Sains</span>
<span class="text-xs font-bold flex items-center gap-1 text-brand-dark">⭐ 4.7 <span class="text-brand-muted text-[10px]">(6.3k)</span></span>
</div>
<p class="text-xs text-brand-muted line-clamp-2 font-medium">
              Mahakarya pemikiran rasional bangsa dalam membongkar logika mistika menuju bangsa merdeka berilmu.
            </p>
</div>
<div class="mt-4 pt-3 border-t-2 border-brand-dark/10">
<a class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs w-full py-2.5 justify-center" href="#baca">
              Baca Sekarang →
            </a>
</div>
</div>
</div>
</section>
<!-- END: FeaturedBooksSection -->
<!-- BEGIN: TopRankingsSection -->
<!-- Archetyped directly from "Friends 143" ranking podium in the reference image -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-purpose="top-rankings" id="peringkat">
<!-- Left Card: Top Books This Week with 6 colored bars (#1 - #6) -->
<div class="neo-card p-6 lg:col-span-6 flex flex-col justify-between" data-purpose="top-books-podium">
<div class="flex items-center justify-between mb-4">
<div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">Buku Terpopuler Minggu Ini</h3>
<p class="text-xs text-brand-muted font-medium">Berdasarkan total halaman yang dibaca pembaca aktif</p>
</div>
<button class="neo-pill bg-brand-yellow hover:bg-[#eab035] px-3 py-1 text-xs font-bold">
            +Semua
          </button>
</div>
<!-- 6 Stylized Ranking Bars matching Reference exact colors & structure -->
<div class="pt-10 pb-2">
<div class="h-44 flex items-end justify-between gap-2 px-1">
<!-- Rank #1: Green Bar (Tallest) -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-yellow text-xs font-black flex items-center justify-center shadow-sm">
                📖
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Bumi M.</span>
<div class="w-full h-36 rounded-2xl border-2 border-brand-dark bg-brand-green flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#1</span>
</div>
</div>
<!-- Rank #2: Coral Bar -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-coral-light text-xs font-black flex items-center justify-center shadow-sm">
                ☕
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Kretek</span>
<div class="w-full h-28 rounded-2xl border-2 border-brand-dark bg-brand-coral flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#2</span>
</div>
</div>
<!-- Rank #3: Yellow Bar -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-yellow-light text-xs font-black flex items-center justify-center shadow-sm">
                🏛️
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Teras</span>
<div class="w-full h-24 rounded-2xl border-2 border-brand-dark bg-brand-yellow flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#3</span>
</div>
</div>
<!-- Rank #4: Blue Bar -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-blue-light text-xs font-black flex items-center justify-center shadow-sm">
                💡
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Madilog</span>
<div class="w-full h-20 rounded-2xl border-2 border-brand-dark bg-brand-blue flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#4</span>
</div>
</div>
<!-- Rank #5: Fresh Green Bar -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-green-light text-xs font-black flex items-center justify-center shadow-sm">
                🌈
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Pelangi</span>
<div class="w-full h-16 rounded-2xl border-2 border-brand-dark bg-brand-green flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#5</span>
</div>
</div>
<!-- Rank #6: Coral Bar -->
<div class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark bg-brand-peach text-xs font-black flex items-center justify-center shadow-sm">
                🚢
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">Wijck</span>
<div class="w-full h-12 rounded-2xl border-2 border-brand-dark bg-brand-coral flex flex-col items-center justify-start pt-2">
<span class="text-xs font-black text-brand-dark">#6</span>
</div>
</div>
</div>
</div>
<div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs font-bold text-brand-muted">
<span>Diperbarui setiap Senin pukul 00.00 WIB</span>
<span class="text-brand-dark">Total 14,230 Pembaca Pekan Ini</span>
</div>
</div>
<!-- Right Card: Author Rankings & Community Highlights -->
<div class="neo-card p-6 lg:col-span-6 flex flex-col justify-between" data-purpose="top-authors-list">
<div>
<div class="flex items-center justify-between mb-4">
<div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">Penulis Terfavorit</h3>
<p class="text-xs text-brand-muted font-medium">Berdasarkan jumlah kutipan dan buku terunduh</p>
</div>
<span class="neo-pill bg-brand-green-light px-3 py-1 text-xs font-bold text-brand-dark">
              Komunitas
            </span>
</div>
<!-- Authors list with rank avatars -->
<div class="space-y-3 mt-4">
<!-- Author 1 -->
<div class="flex items-center justify-between p-2.5 rounded-xl border-1.5 border-brand-dark bg-white hover:bg-gray-50">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full border-2 border-brand-dark bg-brand-yellow flex items-center justify-center font-extrabold text-sm text-brand-dark">
                  PT
                </div>
<div>
<h4 class="font-bold text-sm text-brand-dark">Pramoedya Ananta Toer</h4>
<p class="text-[11px] text-brand-muted font-medium">18 Karya dalam Domain Publik • 45.2k Pembaca</p>
</div>
</div>
<span class="neo-pill bg-brand-green text-brand-dark text-xs px-2.5 py-1">Top #1</span>
</div>
<!-- Author 2 -->
<div class="flex items-center justify-between p-2.5 rounded-xl border-1.5 border-brand-dark bg-white hover:bg-gray-50">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full border-2 border-brand-dark bg-brand-coral flex items-center justify-center font-extrabold text-sm text-brand-dark">
                  BH
                </div>
<div>
<h4 class="font-bold text-sm text-brand-dark">Buya Hamka</h4>
<p class="text-[11px] text-brand-muted font-medium">Sastra &amp; Tafsir Klasik • 38.6k Pembaca</p>
</div>
</div>
<span class="neo-pill bg-brand-coral-light text-brand-dark text-xs px-2.5 py-1">Top #2</span>
</div>
<!-- Author 3 -->
<div class="flex items-center justify-between p-2.5 rounded-xl border-1.5 border-brand-dark bg-white hover:bg-gray-50">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full border-2 border-brand-dark bg-brand-blue flex items-center justify-center font-extrabold text-sm text-brand-dark">
                  SD
                </div>
<div>
<h4 class="font-bold text-sm text-brand-dark">Sapardi Djoko Damono</h4>
<p class="text-[11px] text-brand-muted font-medium">Kumpulan Sajak &amp; Puisi • 31.4k Pembaca</p>
</div>
</div>
<span class="neo-pill bg-brand-blue-light text-brand-dark text-xs px-2.5 py-1">Top #3</span>
</div>
<!-- Author 4 -->
<div class="flex items-center justify-between p-2.5 rounded-xl border-1.5 border-brand-dark bg-white hover:bg-gray-50">
<div class="flex items-center gap-3">
<div class="w-9 h-9 rounded-full border-2 border-brand-dark bg-brand-green flex items-center justify-center font-extrabold text-sm text-brand-dark">
                  TM
                </div>
<div>
<h4 class="font-bold text-sm text-brand-dark">Tan Malaka</h4>
<p class="text-[11px] text-brand-muted font-medium">Pemikiran Bangsa &amp; Filsafat • 29.8k Pembaca</p>
</div>
</div>
<span class="neo-pill bg-gray-100 text-brand-dark text-xs px-2.5 py-1">Top #4</span>
</div>
</div>
</div>
<div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
<a class="text-xs font-bold text-brand-dark hover:underline" href="#penulis">
            Lihat direktori 350+ penulis →
          </a>
<span class="text-xs font-semibold text-brand-muted">Arsip berlisensi terbuka</span>
</div>
</div>
</section>
<!-- END: TopRankingsSection -->
<!-- BEGIN: CategoryGridSection -->
<!-- Grid of 8 outlined cards with pastel colors -->
<section class="space-y-6" data-purpose="browse-by-category" id="kategori">
<div>
<span class="neo-pill bg-brand-blue-light px-3.5 py-1 text-xs font-bold text-brand-dark mb-1">
          🗂️ Jelajahi Menurut Minat
        </span>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight">
          Koleksi Beragam Sesuai Selera
        </h2>
<p class="text-sm text-brand-muted font-medium mt-1">
          Temukan lebih dari 25.000 judul yang telah terkurasi rapi dan siap dibaca langsung.
        </p>
</div>
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5">
<!-- Category 1 -->
<a class="neo-card p-5 bg-brand-coral-light/70 hover:bg-brand-coral-light flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            📚
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Sastra &amp; Novel</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">4,250 buku</p>
</div>
</a>
<!-- Category 2 -->
<a class="neo-card p-5 bg-brand-yellow-light/80 hover:bg-brand-yellow-light flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            🏛️
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Sejarah Nusantara</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">3,120 naskah</p>
</div>
</a>
<!-- Category 3 -->
<a class="neo-card p-5 bg-brand-blue-light/70 hover:bg-brand-blue-light flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            🔬
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Sains &amp; Teknologi</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">2,890 buku</p>
</div>
</a>
<!-- Category 4 -->
<a class="neo-card p-5 bg-brand-green-light/70 hover:bg-brand-green-light flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            🌱
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Anak &amp; Dongeng</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">1,940 judul</p>
</div>
</a>
<!-- Category 5 -->
<a class="neo-card p-5 bg-brand-lavender/70 hover:bg-brand-lavender flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            🕊️
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Agama &amp; Filsafat</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">2,340 naskah</p>
</div>
</a>
<!-- Category 6 -->
<a class="neo-card p-5 bg-brand-peach/70 hover:bg-brand-peach flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            🎨
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Seni &amp; Budaya</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">1,480 katalog</p>
</div>
</a>
<!-- Category 7 -->
<a class="neo-card p-5 bg-[#E0F2F1]/80 hover:bg-[#E0F2F1] flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            💬
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Komik &amp; Grafis</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">920 edisi</p>
</div>
</a>
<!-- Category 8 -->
<a class="neo-card p-5 bg-[#FFF3E0]/80 hover:bg-[#FFF3E0] flex flex-col justify-between group" href="#kategori">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-2xl group-hover:scale-105 transition-transform">
            📜
          </div>
<div class="mt-4">
<h3 class="font-bold text-base text-brand-dark leading-tight">Naskah Kuno &amp; Arsip</h3>
<p class="text-xs text-brand-muted font-semibold mt-1">810 manuskrip</p>
</div>
</a>
</div>
</section>
<!-- END: CategoryGridSection -->
<!-- BEGIN: SpecialCollections -->
<!-- Two horizontal cards modeled after the "Watch faces" style card in reference -->
<section class="space-y-6" data-purpose="curated-special-collections" id="koleksi-khusus">
<div class="flex items-center justify-between">
<div>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight">
            Koleksi Khusus Pilihan
          </h2>
<p class="text-sm text-brand-muted font-medium mt-1">
            Arsip tematik eksklusif yang dipindai langsung dari naskah fisik bernilai sejarah tinggi.
          </p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Special Collection Card 1 -->
<div class="neo-card p-6 flex items-center justify-between bg-white hover:-translate-y-0.5">
<div class="space-y-2 pr-4">
<span class="neo-pill bg-brand-coral-light text-brand-dark px-3 py-0.5 text-xs font-bold">Koleksi Abadi</span>
<h3 class="font-display font-extrabold text-xl text-brand-dark leading-snug">
              Koleksi Klasik Indonesia
            </h3>
<p class="text-xs text-brand-muted font-medium leading-relaxed">
              Dunia sastra era Balai Pustaka &amp; Pujangga Baru. Dari Marah Roesli, Sutan Takdir Alisjahbana, hingga Chairil Anwar.
            </p>
<div class="pt-2">
<a class="text-xs font-extrabold text-brand-dark underline hover:text-brand-coral" href="#koleksi">
                Jelajahi 140+ Edisi Klasik →
              </a>
</div>
</div>
<!-- Circular Graphic Emblem (Like the "09:30" watch face in reference) -->
<div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-brand-dark bg-[#2C3E50] p-1.5 shrink-0 flex items-center justify-center relative overflow-hidden shadow-sm">
<div class="w-full h-full rounded-full border border-dashed border-brand-yellow flex flex-col items-center justify-center text-center p-1">
<span class="text-lg">📜</span>
<span class="text-[9px] font-black text-brand-yellow tracking-widest uppercase">1920-50</span>
</div>
</div>
</div>
<!-- Special Collection Card 2 -->
<div class="neo-card p-6 flex items-center justify-between bg-white hover:-translate-y-0.5">
<div class="space-y-2 pr-4">
<span class="neo-pill bg-brand-green-light text-brand-dark px-3 py-0.5 text-xs font-bold">Edisi Bergambar</span>
<h3 class="font-display font-extrabold text-xl text-brand-dark leading-snug">
              Cerita Rakyat &amp; Dongeng Nusantara
            </h3>
<p class="text-xs text-brand-muted font-medium leading-relaxed">
              Kisah pengantar tidur legendaris Sabang sampai Merauke lengkap dengan ilustrasi digital ramah anak.
            </p>
<div class="pt-2">
<a class="text-xs font-extrabold text-brand-dark underline hover:text-brand-green" href="#koleksi">
                Buka Dongeng Bergambar →
              </a>
</div>
</div>
<!-- Circular Graphic Emblem -->
<div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-2 border-brand-dark bg-brand-yellow p-1.5 shrink-0 flex items-center justify-center relative overflow-hidden shadow-sm">
<div class="w-full h-full rounded-full border border-dashed border-brand-dark flex flex-col items-center justify-center text-center p-1 bg-white">
<span class="text-lg">🌙✨</span>
<span class="text-[9px] font-black text-brand-dark tracking-wide uppercase">Dongeng</span>
</div>
</div>
</div>
</div>
</section>
<!-- END: SpecialCollections -->
<!-- BEGIN: WhyLibfreeSection -->
<!-- 3 Playful Outlined Benefit Cards -->
<section class="space-y-6" data-purpose="why-libfree" id="tentang">
<div class="text-center max-w-2xl mx-auto">
<span class="neo-pill bg-brand-yellow-light px-3.5 py-1 text-xs font-bold text-brand-dark mb-2">
          ✨ Filosofi Terbuka
        </span>
<h2 class="font-display font-extrabold text-3xl sm:text-4xl text-brand-dark tracking-tight">
          Mengapa Harus libfree?
        </h2>
<p class="text-sm text-brand-muted font-medium mt-2">
          Kami percaya bahwa akses pada ilmu pengetahuan dan karya sastra adalah hak asasi setiap pembelajar tanpa terhalang paywall.
        </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
<!-- Benefit 1 -->
<div class="neo-card p-6 bg-white space-y-4 text-left">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-brand-coral flex items-center justify-center text-xl text-white font-black shadow-sm">
            01
          </div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">
            100% Gratis Tanpa Batas
          </h3>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Tidak ada masa uji coba (trial), tidak ada kartu kredit, dan tanpa batasan jumlah buku yang bisa dibaca per bulan.
          </p>
<div class="pt-2">
<span class="text-xs font-bold text-brand-coral">Bebas selamanya →</span>
</div>
</div>
<!-- Benefit 2 -->
<div class="neo-card p-6 bg-white space-y-4 text-left">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-brand-yellow flex items-center justify-center text-xl text-brand-dark font-black shadow-sm">
            02
          </div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">
            Simpan &amp; Baca Offline
          </h3>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Dukung browser e-Reader modern. Unduh format EPUB dan PDF standar industri yang dapat disalin ke Kindle atau Kobo.
          </p>
<div class="pt-2">
<span class="text-xs font-bold text-brand-dark">Standar terbuka →</span>
</div>
</div>
<!-- Benefit 3 -->
<div class="neo-card p-6 bg-white space-y-4 text-left">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-brand-green flex items-center justify-center text-xl text-white font-black shadow-sm">
            03
          </div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">
            Komunitas &amp; Donasi Naskah
          </h3>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Dikelola bersama para relawan arsiparis, penerjemah, dan komunitas sastra untuk melestarikan memori literasi nusantara.
          </p>
<div class="pt-2">
<span class="text-xs font-bold text-brand-green">Didukung sukarelawan →</span>
</div>
</div>
</div>
</section>
<!-- END: WhyLibfreeSection -->
<!-- BEGIN: ContributionBanner -->
<!-- Green Panel CTA inspired by the bottom green card style in reference image -->
<section class="rounded-3xl border-2 border-brand-dark bg-brand-green p-4 sm:p-8 shadow-solid-lg" data-purpose="contribution-cta">
<div class="neo-card p-6 sm:p-10 bg-white max-w-4xl mx-auto text-center space-y-6">
<!-- Little Green Capsule -->
<div class="w-12 h-2.5 mx-auto rounded-full bg-brand-green border border-brand-dark"></div>
<div class="space-y-3">
<span class="neo-pill bg-brand-green-light px-3 py-1 text-xs font-bold text-brand-dark">
            🤝 Gerakan Buku Terbuka
          </span>
<h2 class="font-display font-extrabold text-2xl sm:text-4xl text-brand-dark tracking-tight leading-tight sm:leading-10">
            Punya buku domain publik atau naskah langka untuk dibagikan?
          </h2>
<p class="text-sm sm:text-base text-brand-muted font-medium max-w-2xl mx-auto leading-relaxed sm:leading-6">
            Bantu jutaan pelajar dan pembaca di seluruh pelosok Indonesia dengan mendigitalkan karya-karya klasik yang bebas hak cipta komersial.
          </p>
</div>
<!-- CTA Action Buttons -->
<div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
<a class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark px-7 py-3 text-sm sm:text-base w-full sm:w-auto shadow-sm" href="#unggah">
            Unggah Buku Sekarang 📚
          </a>
<a class="neo-pill bg-white hover:bg-gray-100 text-brand-dark px-7 py-3 text-sm sm:text-base w-full sm:w-auto" href="#panduan">
            Panduan Hak Cipta
          </a>
</div>
<div class="pt-2 flex flex-wrap items-center justify-center gap-6 text-xs text-brand-muted font-semibold">
<span>✓ Lisensi Creative Commons</span>
<span>✓ Verifikasi Kurator 1x24 Jam</span>
<span>✓ Metadata Terstandar</span>
</div>
</div>
</section>
<!-- END: ContributionBanner -->
<!-- BEGIN: FAQSection -->
<!-- Frequently Asked Questions with clean 2px outlines -->
<section class="space-y-6 max-w-3xl mx-auto" data-purpose="faq-section">
<div class="text-center">
<span class="neo-pill bg-brand-coral-light px-3 py-1 text-xs font-bold text-brand-dark mb-1">
          💡 Tanya Jawab
        </span>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight">
          Pertanyaan yang Sering Diajukan
        </h2>
</div>
<div class="space-y-3 pt-2">
<!-- FAQ 1 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors" open="">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Apakah libfree benar-benar 100% gratis tanpa biaya tersembunyi?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Ya, sepenuhnya gratis. Kami tidak mengenakan biaya langganan, tidak membatasi kuota baca bulanan, dan tidak menyisipkan iklan yang mengganggu kenyamanan membaca. Seluruh operasional didukung oleh donasi komunitas dan organisasi non-profit.
          </p>
</details>
<!-- FAQ 2 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Format apa saja yang tersedia untuk diunduh?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Sebagian besar buku tersedia dalam format <strong>EPUB</strong> (responsif untuk layar ponsel dan tablet), <strong>PDF</strong> (tampilan persis naskah cetak asli), dan format web reader langsung yang bisa dibuka tanpa menginstal aplikasi tambahan.
          </p>
</details>
<!-- FAQ 3 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Bagaimana cara berkontribusi atau mengunggah buku?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Anda dapat mendaftarkan diri sebagai kontributor melalui tombol "Unggah Buku". Naskah yang diunggah harus telah berada dalam domain publik (biasanya 50–70 tahun setelah wafatnya pengarang) atau telah dilisensikan di bawah Creative Commons oleh penulis/penerbitnya.
          </p>
</details>
<!-- FAQ 4 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Apakah saya bisa menandai dan mencatat bagian penting buku?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Tentu saja! Fitur Web Reader libfree dilengkapi alat penanda sorotan (highlighter warna-warni), pembatas buku (bookmark), dan catatan kaki pribadi yang tersimpan aman di akun Anda dan dapat diekspor kapan saja.
          </p>
</details>
</div>
</section>
<!-- END: FAQSection -->
</main>
</template>

<script setup>
import { ref } from 'vue'

// Port of the template's inline handler: clicking a hero quick-tag fills the search box.
const searchInput = ref(null)

function onHeroClick(event) {
  const tag = event.target.closest('.neo-pill[href="#katalog"]')
  if (!tag || !searchInput.value) return

  event.preventDefault()
  searchInput.value.value = tag.textContent.trim()
  searchInput.value.focus()
}
</script>
