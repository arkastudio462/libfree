<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20 pb-28"><div class="flex flex-col w-full">
<!-- TOP HERO & SEARCH CARD -->
<section class="w-full mb-8">
<div class="neo-card p-6 sm:p-10 bg-white shadow-solid relative overflow-hidden">
<!-- Decorative Concentric Circles inside card -->
<div class="absolute -right-16 -top-16 w-56 h-56 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<div class="absolute -right-8 -top-8 w-40 h-40 rounded-full border-2 border-brand-dark/10 pointer-events-none"></div>
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs font-bold text-brand-muted mb-4">
<a class="hover:text-brand-dark transition-colors flex items-center gap-1" href="#">
<span class="material-symbols-outlined text-sm">home</span>
          Beranda
        </a>
<span>/</span>
<span class="text-brand-dark px-2 py-0.5 rounded-full bg-brand-yellow/30 border border-brand-dark/20">Koleksi &amp; Katalog</span>
</nav>
<div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
<div class="max-w-3xl">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-green-light border-2 border-brand-dark text-xs font-extrabold text-brand-dark mb-3">
<span class="w-2 h-2 rounded-full bg-brand-green animate-pulse"></span>
            Akses Publik Bebas Royalti &amp; Terbuka
          </div>
<h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-brand-dark leading-tight sm:leading-10 lg:leading-none">
            Katalog &amp; Koleksi Buku Terbuka
          </h1>
<p class="mt-3 text-sm sm:text-base text-brand-muted font-medium leading-relaxed sm:leading-6 max-w-2xl">
            Jelajahi lebih dari <strong class="text-brand-dark font-bold">25.000 naskah klasik</strong>, ensiklopedia, sastra nusantara, dan buku sains gratis tanpa batasan kuota unduh harian.
          </p>
</div>
<!-- Quick Summary Micro-badge -->
<div class="neo-card-sm p-4 bg-brand-cream border-2 border-brand-dark shrink-0 flex items-center gap-4 self-start lg:self-auto">
<div class="w-11 h-11 rounded-2xl bg-brand-coral border-2 border-brand-dark flex items-center justify-center font-bold text-white shadow-sm">
<span class="material-symbols-outlined text-brand-dark">auto_stories</span>
</div>
<div>
<span class="text-[11px] font-bold text-brand-muted uppercase tracking-wider block">Total Terindeks</span>
<span class="text-xl font-display font-extrabold text-brand-dark">25.420 Naskah</span>
</div>
</div>
</div>
<!-- Search & Command Bar -->
<div class="mt-8 pt-6 border-t-2 border-brand-dark/10">
<form class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
<div class="relative flex-1">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-brand-dark pointer-events-none">search</span>
<input class="w-full neo-pill pl-12 pr-4 py-3 text-sm font-semibold bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:border-brand-dark focus:bg-white text-brand-dark placeholder-brand-muted shadow-[2px_2px_0px_#1A1A1A] transition-all" id="catalog-search" placeholder="Cari judul naskah, nama penulis (cth. Pramoedya, Kartini), atau kata kunci..." type="text"/>
</div>
<div class="flex items-center gap-2">
<button class="neo-pill px-4 py-3 text-sm bg-white hover:bg-brand-lavender border-2 border-brand-dark text-brand-dark font-bold flex items-center gap-1.5 shadow-[2px_2px_0px_#1A1A1A]" id="toggle-filter-mobile" type="button">
<span class="material-symbols-outlined text-lg">tune</span>
<span class="hidden sm:inline">Opsi</span>
</button>
<button class="neo-pill px-6 py-3 text-sm bg-brand-yellow hover:bg-[#ebb035] border-2 border-brand-dark text-brand-dark font-bold shadow-[2px_2px_0px_#1A1A1A] flex items-center justify-center gap-2 shrink-0 cursor-pointer" type="submit">
<span>Cari Buku</span>
<span class="material-symbols-outlined text-base">arrow_forward</span>
</button>
</div>
</form>
</div>
</div>
</section>
<!-- QUICK FILTER PILLS (CAROUSEL / WRAP) -->
<section class="w-full mb-8">
<div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none text-xs font-bold no-scrollbar">
<button class="neo-pill px-4 py-2 bg-brand-dark text-white border-2 border-brand-dark shadow-[2px_2px_0px_#F4B942] whitespace-nowrap">
        Semua (25.420)
      </button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-coral-light text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Sastra &amp; Novel <span class="ml-1 text-[11px] opacity-75 font-normal">4.250</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-yellow-light text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Sejarah Nusantara <span class="ml-1 text-[11px] opacity-75 font-normal">3.120</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-blue-light text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Sains &amp; Teknologi <span class="ml-1 text-[11px] opacity-75 font-normal">2.890</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-green-light text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Filsafat &amp; Agama <span class="ml-1 text-[11px] opacity-75 font-normal">2.340</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-peach text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Komik &amp; Grafis <span class="ml-1 text-[11px] opacity-75 font-normal">920</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-yellow-light text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Anak &amp; Dongeng <span class="ml-1 text-[11px] opacity-75 font-normal">1.940</span>
</button>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-lavender text-brand-dark border-2 border-brand-dark whitespace-nowrap shadow-sm">
        Naskah Kuno &amp; Arsip <span class="ml-1 text-[11px] opacity-75 font-normal">810</span>
</button>
</div>
</section>
<!-- CURATED HERO MINI-BANNER (OnePlus Watch Card Style from Inspiration) -->
<section class="w-full mb-8">
<div class="neo-card p-5 sm:p-6 bg-gradient-to-r from-brand-peach via-white to-brand-green-light border-2 border-brand-dark shadow-solid flex flex-col md:flex-row items-center justify-between gap-5">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-2xl bg-brand-yellow border-2 border-brand-dark flex items-center justify-center shrink-0 shadow-sm">
<span class="material-symbols-outlined text-2xl text-brand-dark">workspace_premium</span>
</div>
<div>
<span class="text-[11px] font-bold tracking-wider uppercase bg-brand-dark text-white px-2.5 py-0.5 rounded-full inline-block mb-1">Kurasi Pekan Ini</span>
<h3 class="text-base sm:text-lg font-extrabold font-display text-brand-dark">
            Menelusuri Jejak Perlawanan Sastra Pra-Kemerdekaan (8 Arsip Pilihan)
          </h3>
<p class="text-xs text-brand-muted font-semibold">Tersedia dalam format rekonstruksi digital high-resolution dan cetak ulang EPUB.</p>
</div>
</div>
<a class="neo-pill px-5 py-2.5 bg-brand-dark text-white hover:bg-brand-muted text-xs font-bold border-2 border-brand-dark shadow-sm shrink-0 flex items-center gap-2" href="#koleksi-khusus">
<span>Buka Koleksi Kurasi</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
</div>
</section>
<!-- MAIN CATALOG AREA (2 COLS: SIDEBAR + GRID) -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- LEFT SIDEBAR: FILTERS -->
<aside class="lg:col-span-3 neo-card p-5 bg-white border-2 border-brand-dark shadow-solid space-y-6 sticky top-24">
<!-- Filter Header -->
<div class="flex items-center justify-between pb-3 border-b-2 border-brand-dark/10">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-brand-dark font-bold">tune</span>
<h3 class="font-display font-extrabold text-base text-brand-dark">Filter Buku</h3>
</div>
<button class="text-xs font-bold text-brand-coral hover:underline cursor-pointer" type="button">
          Reset Filter
        </button>
</div>
<!-- Kategori Filter -->
<div class="space-y-3">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Kategori Utama</span>
<div class="space-y-2 text-xs font-semibold text-brand-dark">
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input checked="" class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Sastra Klasik</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">680</span>
</label>
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Sejarah &amp; Biografi</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">420</span>
</label>
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Sains &amp; Matematika</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">310</span>
</label>
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Filsafat &amp; Pemikiran</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">195</span>
</label>
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Cerita Rakyat &amp; Mitos</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">150</span>
</label>
<label class="flex items-center justify-between cursor-pointer group">
<span class="flex items-center gap-2">
<input class="w-4 h-4 rounded text-brand-green border-2 border-brand-dark focus:ring-0 cursor-pointer" type="checkbox"/>
<span>Naskah Kuno Lontar</span>
</span>
<span class="px-2 py-0.5 rounded-full bg-brand-cream border border-brand-dark/20 text-[10px]">85</span>
</label>
</div>
</div>
<!-- Format Unduhan -->
<div class="space-y-3 pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Format Tersedia</span>
<div class="grid grid-cols-2 gap-2 text-xs font-bold">
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-coral-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input checked="" class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox"/>
<span>EPUB</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-yellow-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input checked="" class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox"/>
<span>PDF Asli</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-blue-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox"/>
<span>Teks TXT</span>
</label>
<label class="neo-card-sm p-2 bg-brand-cream hover:bg-brand-green-light border border-brand-dark flex items-center gap-1.5 cursor-pointer">
<input class="w-3.5 h-3.5 rounded text-brand-dark border-brand-dark focus:ring-0" type="checkbox"/>
<span>Buku Suara</span>
</label>
</div>
</div>
<!-- Periode / Era Sastra -->
<div class="space-y-2 pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Era Kepenulisan</span>
<div class="space-y-1.5 text-xs font-semibold text-brand-dark">
<label class="flex items-center gap-2 cursor-pointer">
<input class="text-brand-yellow border-2 border-brand-dark focus:ring-0" name="era" type="radio"/>
<span>Semua Era</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input checked="" class="text-brand-yellow border-2 border-brand-dark focus:ring-0" name="era" type="radio"/>
<span>Balai Pustaka (1920–1942)</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="text-brand-yellow border-2 border-brand-dark focus:ring-0" name="era" type="radio"/>
<span>Pujangga Baru (1933–1942)</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="text-brand-yellow border-2 border-brand-dark focus:ring-0" name="era" type="radio"/>
<span>Angkatan '45 (1945–1953)</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="text-brand-yellow border-2 border-brand-dark focus:ring-0" name="era" type="radio"/>
<span>Kontemporer Terbuka</span>
</label>
</div>
</div>
<!-- Target Panjang Halaman (Inspiration: Health Progress Bar style) -->
<div class="space-y-3 pt-3 border-t-2 border-brand-dark/10">
<div class="flex items-center justify-between">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark">Ketebalan Buku</span>
<span class="text-[11px] font-bold text-brand-green">100 - 350 Hal</span>
</div>
<div class="w-full bg-brand-cream h-4 rounded-full border-2 border-brand-dark p-0.5 relative flex items-center overflow-hidden">
<div class="h-full bg-brand-coral rounded-full w-[65%] transition-all"></div>
</div>
<div class="flex justify-between text-[10px] font-bold text-brand-muted">
<span>&lt; 100 Hal</span>
<span>100–300 Hal</span>
<span>&gt; 500 Hal</span>
</div>
</div>
<!-- Status Hak Cipta -->
<div class="space-y-2 pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block">Lisensi Naskah</span>
<div class="space-y-1.5 text-xs font-semibold">
<label class="flex items-center gap-2 cursor-pointer">
<input checked="" class="w-4 h-4 rounded text-brand-blue border-2 border-brand-dark focus:ring-0" type="checkbox"/>
<span>Domain Publik (Bebas)</span>
</label>
<label class="flex items-center gap-2 cursor-pointer">
<input class="w-4 h-4 rounded text-brand-blue border-2 border-brand-dark focus:ring-0" type="checkbox"/>
<span>Creative Commons (CC-BY)</span>
</label>
</div>
</div>
<!-- Rating Minimum -->
<div class="pt-3 border-t-2 border-brand-dark/10">
<span class="text-xs font-bold uppercase tracking-wider text-brand-dark block mb-2">Rating Pembaca</span>
<div class="flex gap-1.5">
<button class="neo-pill flex-1 py-1.5 text-xs bg-brand-yellow font-bold border-2 border-brand-dark" type="button">★ 4.5+</button>
<button class="neo-pill flex-1 py-1.5 text-xs bg-brand-cream hover:bg-white font-bold border-2 border-brand-dark" type="button">★ 4.0+</button>
<button class="neo-pill flex-1 py-1.5 text-xs bg-brand-cream hover:bg-white font-bold border-2 border-brand-dark" type="button">Semua</button>
</div>
</div>
</aside>
<!-- RIGHT: MAIN CATALOG & GRID -->
<div class="lg:col-span-9 space-y-6">
<!-- TOOLBAR: STATUS, ACTIVE TAGS, SORT, VIEW SWITCHER -->
<div class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col md:flex-row md:items-center justify-between gap-4">
<div class="space-y-1.5">
<div class="flex items-center gap-2">
<span class="w-2.5 h-2.5 rounded-full bg-brand-green border border-brand-dark"></span>
<span class="text-xs sm:text-sm font-extrabold text-brand-dark">
              Menampilkan <span class="underline decoration-brand-yellow decoration-2">1–12</span> dari 1.420 Buku Terverifikasi
            </span>
</div>
<!-- Active Tags -->
<div class="flex flex-wrap items-center gap-1.5">
<span class="neo-pill px-2.5 py-0.5 text-[11px] bg-brand-coral-light border border-brand-dark text-brand-dark font-bold flex items-center gap-1">
              Sastra Klasik
              <button class="hover:text-brand-coral text-xs" title="Hapus filter">✕</button>
</span>
<span class="neo-pill px-2.5 py-0.5 text-[11px] bg-brand-yellow-light border border-brand-dark text-brand-dark font-bold flex items-center gap-1">
              Format EPUB
              <button class="hover:text-brand-coral text-xs" title="Hapus filter">✕</button>
</span>
<span class="neo-pill px-2.5 py-0.5 text-[11px] bg-brand-green-light border border-brand-dark text-brand-dark font-bold flex items-center gap-1">
              Domain Publik
              <button class="hover:text-brand-coral text-xs" title="Hapus filter">✕</button>
</span>
</div>
</div>
<!-- Controls: Sort & Grid/List view -->
<div class="flex items-center gap-3 shrink-0 self-end md:self-auto">
<div class="flex items-center gap-1.5 text-xs font-bold">
<span class="text-brand-muted hidden sm:inline">Urutkan:</span>
<select class="neo-pill text-xs font-bold py-1.5 px-3 bg-brand-cream border-2 border-brand-dark focus:ring-0 focus:border-brand-dark cursor-pointer shadow-sm">
<option>Paling Populer</option>
<option>Terbaru Diunggah</option>
<option>Rating Tertinggi</option>
<option>Abjad Judul (A–Z)</option>
<option>Tahun Terbit (Terlama)</option>
</select>
</div>
<div class="flex items-center bg-brand-cream p-1 rounded-full border-2 border-brand-dark shadow-sm">
<button class="w-7 h-7 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center text-brand-dark shadow-xs" title="Tampilan Grid">
<span class="material-symbols-outlined text-base">grid_view</span>
</button>
<button class="w-7 h-7 rounded-full hover:bg-white flex items-center justify-center text-brand-muted transition-colors" title="Tampilan List">
<span class="material-symbols-outlined text-base">view_list</span>
</button>
</div>
</div>
</div>
<!-- BOOK CARDS GRID (12 BOOKS) -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
<!-- 1. Bumi Manusia -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<!-- Book Cover Art Container -->
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-peach overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-yellow text-brand-dark font-extrabold border border-brand-dark">Klasik</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB • PDF</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<!-- Book Spine Simulation -->
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-coral block uppercase">Tetralogi Buru #1</span>
<h4 class="font-display font-extrabold text-xs text-brand-dark leading-tight mt-0.5">Bumi Manusia</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Pramoedya A. T.</div>
</div>
</div>
<!-- Meta info -->
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-coral font-bold">Pramoedya Ananta Toer</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.9
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Bumi Manusia
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Kisah perjuangan Minke di tengah pergolakan sosial kolonial Hindia Belanda awal abad ke-20.
              </p>
</div>
</div>
<!-- Footer details & Action -->
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">535 Halaman</span>
<div class="flex gap-1.5">
<RouterLink class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" to="/buku/bumi-manusia">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</RouterLink>
</div>
</div>
</article>
<!-- 2. Tenggelamnya Kapal Van der Wijck -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-blue-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-blue text-brand-dark font-extrabold border border-brand-dark">Roman</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-blue block uppercase">Balai Pustaka</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Kapal Van der Wijck</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Buya Hamka</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-blue font-bold">Hamka (Prof. Dr.)</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.8
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Tenggelamnya Kapal Van der Wijck
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Tragedi asmara Zainuddin dan Hayati yang terhadang benteng adat Minangkabau yang kaku.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">340 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 3. Madilog -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-yellow-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-coral text-white font-extrabold border border-brand-dark">Filsafat</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">PDF</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:-rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-coral block uppercase">Masterpiece 1943</span>
<h4 class="font-display font-extrabold text-xs text-brand-dark leading-tight mt-0.5">MADILOG</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Tan Malaka</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-coral font-bold">Tan Malaka</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.7
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Madilog
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Materialisme, Dialektika, dan Logika: Sintesis pemikiran pembebasan akal sehat nusantara.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">460 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 4. Salah Asuhan -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-green-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-green text-white font-extrabold border border-brand-dark">BP 1928</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-green block uppercase">Seri Klasik</span>
<h4 class="font-display font-extrabold text-xs text-brand-dark leading-tight mt-0.5">Salah Asuhan</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Abdul Muis</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-green font-bold">Abdul Muis</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.6
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Salah Asuhan
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Konflik psikologis dan identitas Hanafi akibat keterasingan budaya timur dan barat.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">280 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 5. Layar Terkembang -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-lavender overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-dark text-white font-extrabold border border-brand-dark">Pujangga Baru</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:-rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-dark block uppercase">Pelopor Modern</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Layar Terkembang</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Sutan Takdir A.</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-dark font-bold">S. Takdir Alisjahbana</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.5
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Layar Terkembang
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Kemajuan emansipasi perempuan dan pergolakan cita-cita pembaharuan bangsa muda.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">210 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 6. Habis Gelap Terbitlah Terang -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-coral-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-coral text-white font-extrabold border border-brand-dark">Surat &amp; Sejarah</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB • PDF</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-coral block uppercase">Dokumen Bangsa</span>
<h4 class="font-display font-extrabold text-[10px] text-brand-dark leading-tight mt-0.5">Habis Gelap Terbitlah Terang</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">R.A. Kartini</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-coral font-bold">R.A. Kartini</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.9
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Habis Gelap Terbitlah Terang
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Himpunan surat-surat visioner Kartini kepada sahabat penanya mengenai hak pendidikan wanita.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">320 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 7. Atheis -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-peach overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-green text-white font-extrabold border border-brand-dark">Sastra 1949</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-green block uppercase">Psikologis</span>
<h4 class="font-display font-extrabold text-xs text-brand-dark leading-tight mt-0.5">Atheis</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Achdiat K. M.</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-green font-bold">Achdiat K. Mihardja</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.7
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Atheis
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Dilema Hasan yang taat menghadapi arus modernisme, marxisme, dan nihilisme di Bandung.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">240 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 8. Siti Nurbaya -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-yellow-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-yellow text-brand-dark font-extrabold border border-brand-dark">Koleksi Abadi</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:-rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-dark block uppercase">Kasih Tak Sampai</span>
<h4 class="font-display font-extrabold text-xs text-brand-dark leading-tight mt-0.5">Siti Nurbaya</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Marah Roesli</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-dark font-bold">Marah Roesli</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.6
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Siti Nurbaya
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Tragedi cinta Samsulbahri dan Siti Nurbaya serta cengkeraman rentenir Datuk Meringgih.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">310 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 9. Robohnya Surau Kami -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-blue-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-blue text-brand-dark font-extrabold border border-brand-dark">Cerpen Klasik</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-blue block uppercase">Antologi Satir</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Robohnya Surau Kami</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">A.A. Navis</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-blue font-bold">A.A. Navis</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.8
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Robohnya Surau Kami
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Kritik tajam nan jenaka terhadap kesalehan ritual yang melupakan tanggung jawab duniawi.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">160 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 10. Babad Tanah Jawi -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-lavender overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-coral text-white font-extrabold border border-brand-dark">Kronik</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">PDF</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:-rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-coral block uppercase">Naskah Klasik</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Babad Tanah Jawi</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Anonim / Keraton</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-dark font-bold">Naskah Mataram</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.7
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Babad Tanah Jawi
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Silsilah raja-raja dan mitologi pembentukan kerajaan di pulau Jawa dari era Hindu ke Islam.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">520 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 11. Si Doel Anak Betawi -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-coral-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-yellow text-brand-dark font-extrabold border border-brand-dark">Remaja</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">EPUB</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-yellow block uppercase">Cerita Rakyat</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Si Doel</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Aman Datuk M.</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-coral font-bold">Aman Datuk Madjoindo</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.5
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Si Doel Anak Betawi
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Potret ceria keseharian bocah Batavia tempo dulu mempertahankan adat dan semangat belajar.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">190 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
<!-- 12. Hikayat Hang Tuah -->
<article class="neo-card p-4 bg-white border-2 border-brand-dark shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-all">
<div>
<div class="relative w-full h-48 rounded-2xl border-2 border-brand-dark bg-brand-green-light overflow-hidden flex items-center justify-center mb-4 p-4">
<div class="absolute top-2 left-2 flex gap-1 z-10">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-green text-white font-extrabold border border-brand-dark">Epik Melayu</span>
<span class="neo-pill px-2 py-0.5 text-[10px] bg-white text-brand-dark font-bold border border-brand-dark">PDF</span>
</div>
<button class="absolute top-2 right-2 w-7 h-7 rounded-full bg-white border border-brand-dark flex items-center justify-center hover:bg-brand-coral-light transition-colors z-10" title="Simpan ke Rak">
<span class="material-symbols-outlined text-sm">bookmark_border</span>
</button>
<div class="w-28 h-40 bg-white border-2 border-brand-dark rounded-r-lg shadow-md p-2 flex flex-col justify-between transform group-hover:-rotate-1 transition-transform">
<div class="border-b border-brand-dark/20 pb-1">
<span class="text-[8px] font-bold text-brand-green block uppercase">Sastra Melayu</span>
<h4 class="font-display font-extrabold text-[11px] text-brand-dark leading-tight mt-0.5">Hikayat Hang Tuah</h4>
</div>
<div class="text-[8px] font-bold text-brand-muted">Arsip Klasik</div>
</div>
</div>
<div class="space-y-1 mb-3">
<div class="flex items-center justify-between text-xs font-bold">
<span class="text-brand-green font-bold">Sastra Maritim Melayu</span>
<span class="flex items-center gap-1 text-brand-dark bg-brand-yellow/40 px-2 py-0.5 rounded-full border border-brand-dark/20">
<span class="text-xs">★</span> 4.8
                </span>
</div>
<h3 class="font-display font-extrabold text-lg text-brand-dark group-hover:text-brand-green transition-colors">
                Hikayat Hang Tuah
              </h3>
<p class="text-xs text-brand-muted font-medium line-clamp-2">
                Kepahlawanan laksamana legendaris Malaka mempertahankan marwah kesultanan di selat Malaka.
              </p>
</div>
</div>
<div class="pt-3 border-t-2 border-brand-dark/10 flex items-center justify-between gap-2 mt-auto">
<span class="text-[11px] font-bold text-brand-muted">480 Halaman</span>
<a class="neo-pill px-3 py-1.5 text-xs bg-brand-yellow hover:bg-[#ebb035] text-brand-dark font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1" href="#">
<span>Baca</span>
<span class="material-symbols-outlined text-sm">open_in_new</span>
</a>
</div>
</article>
</div>
<!-- PAGINATION BAR (Playful Neo-Pill UI) -->
<div class="neo-card p-4 sm:p-5 bg-white border-2 border-brand-dark shadow-solid flex flex-col sm:flex-row items-center justify-between gap-4">
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-cream text-brand-dark text-xs font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1 w-full sm:w-auto justify-center">
<span class="material-symbols-outlined text-sm">arrow_back</span>
<span>Sebelumnya</span>
</button>
<div class="flex items-center gap-1.5 overflow-x-auto text-xs font-bold">
<button class="w-8 h-8 rounded-full bg-brand-yellow text-brand-dark border-2 border-brand-dark font-extrabold flex items-center justify-center shadow-xs">1</button>
<button class="w-8 h-8 rounded-full bg-white hover:bg-brand-yellow-light text-brand-dark border border-brand-dark/30 flex items-center justify-center">2</button>
<button class="w-8 h-8 rounded-full bg-white hover:bg-brand-yellow-light text-brand-dark border border-brand-dark/30 flex items-center justify-center">3</button>
<button class="w-8 h-8 rounded-full bg-white hover:bg-brand-yellow-light text-brand-dark border border-brand-dark/30 hidden sm:flex items-center justify-center">4</button>
<span class="px-1 text-brand-muted">...</span>
<button class="w-8 h-8 rounded-full bg-white hover:bg-brand-yellow-light text-brand-dark border border-brand-dark/30 flex items-center justify-center">118</button>
</div>
<button class="neo-pill px-4 py-2 bg-white hover:bg-brand-cream text-brand-dark text-xs font-bold border-2 border-brand-dark shadow-xs flex items-center gap-1 w-full sm:w-auto justify-center">
<span>Berikutnya</span>
<span class="material-symbols-outlined text-sm">arrow_forward</span>
</button>
</div>
</div>
</div>
<!-- FLOATING WORKOUT/SIGNUP INSPIRED CALLOUT (From Image Component Center) -->
<section class="mt-14 w-full">
<div class="neo-card p-6 sm:p-8 bg-brand-green-light border-2 border-brand-dark shadow-solid relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
<div class="space-y-2 max-w-xl">
<div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-brand-dark text-xs font-bold text-brand-dark">
<span class="material-symbols-outlined text-sm text-brand-green">volunteer_activism</span>
          Kontribusi Komunitas
        </div>
<h3 class="text-2xl font-extrabold font-display text-brand-dark tracking-tight">
          Punya Koleksi Naskah Langka atau Terjemahan Bebas?
        </h3>
<p class="text-xs sm:text-sm text-brand-muted font-medium leading-relaxed sm:leading-5">
          Bantu digitalisasi ribuan naskah klasik nusantara. Tim relawan libfree siap membantu kurasi teks, OCR scanning, serta konversi EPUB ramah gawai.
        </p>
</div>
<div class="flex flex-col sm:flex-row items-center gap-3 shrink-0 w-full md:w-auto">
<a class="neo-pill w-full sm:w-auto px-6 py-3 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark border-2 border-brand-dark font-extrabold text-xs shadow-solid text-center" href="#unggah">
          Mulai Unggah Naskah
        </a>
<a class="neo-pill w-full sm:w-auto px-5 py-3 bg-white hover:bg-gray-50 text-brand-dark border-2 border-brand-dark font-bold text-xs text-center shadow-xs" href="#panduan">
          Pelajari Lisensi CC0
        </a>
</div>
</div>
</section>
</div>
</main>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue'

// Port of the template's inline handlers (bookmark toggle + mobile filter focus),
// scoped to this page instead of the whole document.
const root = ref(null)

function onClick(event) {
  const bookmarkBtn = event.target.closest('button[title="Simpan ke Rak"]')
  if (bookmarkBtn) {
    const icon = bookmarkBtn.querySelector('.material-symbols-outlined')
    if (icon) {
      const isSaved = icon.textContent === 'bookmark'
      icon.textContent = isSaved ? 'bookmark_border' : 'bookmark'
      bookmarkBtn.classList.toggle('bg-brand-yellow', !isSaved)
      bookmarkBtn.classList.toggle('bg-white', isSaved)
    }
    return
  }

  if (event.target.closest('#toggle-filter-mobile')) {
    const sidebar = root.value?.querySelector('aside')
    if (!sidebar) return
    sidebar.scrollIntoView({ behavior: 'smooth' })
    sidebar.classList.add('ring-4', 'ring-brand-yellow')
    setTimeout(() => sidebar.classList.remove('ring-4', 'ring-brand-yellow'), 1200)
  }
}

onMounted(() => root.value?.addEventListener('click', onClick))
onBeforeUnmount(() => root.value?.removeEventListener('click', onClick))
</script>
