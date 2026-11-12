<template>
<!-- BEGIN: MainNavbar -->
<!-- Sticky navigation bar with rounded container -->
<header class="sticky top-4 z-50 px-4 sm:px-6 lg:px-8 mb-8 max-w-8xl mx-auto" data-purpose="site-header">
<div class="neo-card py-3.5 px-5 md:px-7 flex items-center justify-between shadow-solid transition-all bg-white/95 backdrop-blur-md">
<!-- Brand Logo -->
<RouterLink aria-label="libfree Beranda" class="flex items-center gap-2.5 group" to="/">
<div class="w-10 h-10 rounded-full bg-brand-yellow border-2 border-brand-dark flex items-center justify-center transition-transform group-hover:rotate-6">
<svg class="w-5 h-5 text-brand-dark" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
</div>
<span class="font-display font-extrabold text-2xl tracking-tight text-brand-dark">lib<span class="text-brand-green">free</span></span>
</RouterLink>
<!-- Desktop Navigation Menu -->
<nav aria-label="Menu Utama" class="hidden lg:flex items-center gap-7 text-sm font-bold text-brand-dark">
<RouterLink class="hover:text-brand-green transition-colors" to="/">Beranda</RouterLink>
<RouterLink class="hover:text-brand-green transition-colors" to="/katalog">Jelajahi</RouterLink>
<RouterLink class="hover:text-brand-green transition-colors" to="/#kategori">Kategori</RouterLink>
<RouterLink class="hover:text-brand-green transition-colors" to="/#peringkat">Penulis</RouterLink>
<RouterLink class="hover:text-brand-green transition-colors" to="/#koleksi-khusus">Koleksi</RouterLink>
<RouterLink class="hover:text-brand-green transition-colors" to="/#tentang">Tentang</RouterLink>
</nav>
<!-- Auth and Quick Actions -->
<div class="flex items-center gap-2.5 sm:gap-3">
<a class="neo-pill px-4 py-2 text-xs sm:text-sm bg-white hover:bg-gray-50 text-brand-dark" href="#masuk">
          Masuk
        </a>
<a class="neo-pill px-4 sm:px-5 py-2 text-xs sm:text-sm bg-brand-yellow hover:bg-[#ebae34] text-brand-dark" href="#daftar">
          Daftar Gratis
        </a>
</div>
</div>
</header>
<!-- END: MainNavbar -->
</template>
