<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-20 pb-12 lg:pb-16">
<!-- BEGIN: HeroSection -->
<!-- Hero section with search & workout-inspired registration card -->
<section class="pt-4 lg:pt-8" data-purpose="hero-section" id="hero">
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
            Akses koleksi naskah klasik nusantara yang telah diverifikasi status lisensinya: sastra, sejarah, filsafat, dan cerita rakyat dalam satu perpustakaan terbuka tanpa biaya langganan.
          </p>
<!-- Search Bar Container -->
<div class="pt-2 max-w-xl">
<form class="neo-card p-2 sm:p-2.5 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 bg-white" @submit.prevent="goSearch">
<div class="flex items-center gap-2.5 flex-1 px-3">
<svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
<path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>
<input ref="searchInput" v-model="searchQuery" aria-label="Pencarian buku" class="w-full border-0 p-0 text-sm sm:text-base font-medium placeholder:text-gray-400 focus:ring-0 text-brand-dark bg-transparent" placeholder="Cari judul buku, penulis, topik naskah..." type="text">
</div>
<button class="neo-pill bg-brand-yellow hover:bg-[#eab039] text-brand-dark text-sm px-6 py-3 whitespace-nowrap" type="submit">
                Cari Buku 🔍
              </button>
</form>
<!-- Popular Search Pills -->
<div class="flex flex-wrap items-center gap-2 mt-4 text-xs font-semibold">
<span class="text-brand-muted">Pencarian Populer:</span>
<RouterLink v-for="tag in quickTags" :key="tag" class="neo-pill bg-white px-3 py-1 text-xs hover:bg-gray-100" :to="{ path: '/katalog', query: { q: tag } }">{{ tag }}</RouterLink>
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
        <button type="button" aria-label="Buku sebelumnya" class="w-7 h-7 rounded-full border-1.5 border-brand-dark bg-white hover:bg-gray-100 flex items-center justify-center text-xs font-bold transition-transform active:scale-95 cursor-pointer" @click="prevHero">←</button>
        <button type="button" aria-label="Buku selanjutnya" class="w-7 h-7 rounded-full border-1.5 border-brand-dark bg-brand-yellow hover:bg-[#ebb035] flex items-center justify-center text-xs font-bold transition-transform active:scale-95 cursor-pointer" @click="nextHero">→</button>
      </div>
    </div>

    <!-- Main Book Cover Display Area -->
    <div class="relative overflow-hidden rounded-2xl border-2 border-brand-dark bg-brand-cream p-4">
      <!-- Slides Container -->
      <div id="heroBookSlides" class="relative min-h-[220px]">
        <div v-for="(slide, i) in heroSlides" :key="slide.book.id" class="hero-book-slide transition-all duration-500" :class="i === heroIndex ? 'block opacity-100' : 'hidden opacity-0'">
          <div class="flex flex-col items-center gap-4 text-center sm:flex-row sm:text-left">
            <div class="w-24 h-36 sm:w-28 sm:h-40 shrink-0 rounded-xl border-2 border-brand-dark p-2.5 sm:p-3 flex flex-col justify-between text-white shadow-solid relative overflow-hidden group" :style="{ backgroundColor: slide.hero.hex }">
              <div class="flex justify-between items-start">
                <span class="text-[9px] font-mono tracking-widest uppercase bg-black/40 px-1.5 py-0.5 rounded border border-white/20">{{ slide.hero.badge }}</span>
                <span class="text-base">{{ slide.hero.emoji }}</span>
              </div>
              <div>
                <h4 class="font-display font-black text-sm leading-tight text-white">{{ slide.hero.title_display }}</h4>
                <p class="text-[10px] text-white/80 font-sans mt-0.5">{{ slide.hero.author_display }}</p>
              </div>
              <div class="text-[8px] text-white/60 tracking-wider truncate">{{ slide.hero.kicker }}</div>
            </div>
            <div class="w-full flex-1 space-y-2 text-center sm:w-auto sm:text-left">
              <div class="flex items-center justify-center gap-1.5 flex-wrap sm:justify-start">
                <span v-for="tag in slide.hero.tags" :key="tag.label" class="neo-pill px-2 py-0.5 text-[10px] text-brand-dark font-bold" :class="tag.class">{{ tag.label }}</span>
              </div>
              <h3 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-snug sm:leading-7">{{ slide.hero.title_short ?? slide.book.title }}</h3>
              <p class="text-[11px] text-brand-muted font-medium line-clamp-2 leading-relaxed">{{ slide.hero.synopsis }}</p>
              <div class="text-[11px] font-bold text-brand-dark flex items-center justify-center gap-2 pt-0.5 sm:justify-start">
                <span>⭐ {{ slide.hero.rating_label }}</span>
                <span>•</span>
                <span>{{ slide.book.pages }} Hlm</span>
              </div>
            </div>
          </div>
        </div>
        <div v-if="!heroSlides.length" class="min-h-[220px] flex items-center justify-center text-xs font-bold text-brand-muted">
          Memuat buku…
        </div>
      </div>
    </div>

    <!-- Indicator dots & interactive preview thumbnails -->
    <div class="flex items-center justify-between gap-2 pt-1">
      <div class="flex items-center gap-1.5">
        <button v-for="(slide, i) in heroSlides" :key="slide.book.id" type="button" class="hero-book-dot rounded-full border border-brand-dark transition-all cursor-pointer" :class="i === heroIndex ? 'w-6 h-2 bg-brand-yellow' : 'w-2 h-2 bg-gray-200'" :aria-label="'Buku ' + (i + 1)" @click="heroIndex = i"></button>
      </div>
      <span class="text-[11px] font-bold text-brand-muted" id="heroBookCounter">{{ heroIndex + 1 }} dari {{ heroSlides.length }} Buku</span>
    </div>

    <!-- Action Button -->
    <div class="pt-1">
      <RouterLink v-if="activeHero" class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark px-6 py-2.5 text-sm w-full justify-center shadow-sm font-bold" :to="'/buku/' + activeHero.slug">
        Lihat Buku Ini →
      </RouterLink>
      <span v-else class="neo-pill bg-brand-yellow text-brand-dark px-6 py-2.5 text-sm w-full justify-center shadow-sm font-bold block text-center opacity-60">
        Lihat Buku Ini →
      </span>
    </div>
  </div>

  <!-- Bottom quick stats bar with pill cards -->
  <div class="pt-2.5 pb-0.5 px-2 grid grid-cols-2 gap-2 text-center text-xs font-bold">
    <div class="neo-pill bg-white py-1.5 px-2 sm:px-3 flex items-center justify-center gap-1.5 text-[10px] sm:text-[11px]">
      <span>📖</span>
      <span>Gratis Tanpa Kuota</span>
    </div>
    <div class="neo-pill bg-white py-1.5 px-2 sm:px-3 flex items-center justify-center gap-1.5 text-[10px] sm:text-[11px]">
      <span>⚡</span>
      <span>Baca di Peramban</span>
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
<div class="flex items-center justify-between gap-3">
<div>
<span class="neo-pill bg-brand-yellow px-3 py-1 text-xs font-bold text-brand-dark mb-1">
            ⭐ Kurasi Mingguan
          </span>
<h2 class="font-display font-extrabold text-xl sm:text-3xl text-brand-dark tracking-tight">
            Buku Pilihan Redaksi
          </h2>
</div>
<!-- Navigation buttons with neo outline -->
<div class="hidden sm:flex items-center gap-2">
<button aria-label="Sebelumnya" class="w-10 h-10 rounded-full border-2 border-brand-dark bg-white hover:bg-gray-100 flex items-center justify-center font-bold">
            ←
          </button>
<button aria-label="Selanjutnya" class="w-10 h-10 rounded-full border-2 border-brand-dark bg-brand-yellow hover:bg-[#ebae34] flex items-center justify-center font-bold">
            →
          </button>
</div>
</div>
<!-- 4 Rich Featured Books Grid -->
<div v-if="featuredBooks.length" class="grid grid-cols-2 gap-4 sm:gap-6">
<div v-for="item in featuredBooks" :key="item.book.id" class="neo-card p-4 sm:p-5 flex flex-col justify-between hover:-translate-y-1 group bg-white">
<div>
<!-- Book Cover Art Box -->
<div class="w-full h-40 sm:h-48 rounded-xl border-2 border-brand-dark p-4 flex flex-col justify-between text-white relative shadow-sm overflow-hidden mb-4" :style="{ backgroundColor: item.featured.hex }">
<div class="flex justify-between items-start">
<span class="text-[10px] font-mono tracking-widest uppercase bg-black/40 px-2 py-0.5 rounded border border-white/20">{{ item.featured.badge }}</span>
<span class="text-xl">{{ item.featured.emoji }}</span>
</div>
<div>
<h4 class="font-display font-black text-xl leading-tight text-white">{{ item.featured.title_display }}</h4>
<p class="text-xs text-white/80 font-sans mt-0.5">{{ item.featured.author_display }}</p>
</div>
<div class="text-[9px] text-white/60 tracking-wider">{{ item.featured.kicker }}</div>
</div>
<!-- Details -->
<div class="flex items-center justify-between mb-2">
<span class="neo-pill px-2.5 py-0.5 text-[11px] text-brand-dark font-bold" :class="item.featured.tag.class">{{ item.featured.tag.label }}</span>
<span class="text-xs font-bold flex items-center gap-1 text-brand-dark">⭐ {{ splitRating(item.featured.rating_label).value }} <span class="text-brand-muted text-[10px]">({{ splitRating(item.featured.rating_label).count }})</span></span>
</div>
<p class="text-xs text-brand-muted line-clamp-2 font-medium">
              {{ item.featured.synopsis }}
            </p>
</div>
<div class="mt-4 pt-3 border-t-2 border-brand-dark/10">
<RouterLink class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs w-full py-2.5 justify-center" :to="'/buku/' + item.book.slug">
              Baca Sekarang →
            </RouterLink>
</div>
</div>
</div>
<div v-else class="neo-card p-10 bg-white text-center text-sm font-bold text-brand-muted">
    Memuat buku pilihan…
  </div>
</section>
<!-- END: FeaturedBooksSection -->
<!-- BEGIN: TopRankingsSection -->
<!-- Archetyped directly from "Friends 143" ranking podium in the reference image -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-purpose="top-rankings" id="peringkat">
<!-- Left Card: Top Books This Week with 6 colored bars (#1 - #6) -->
<div class="neo-card p-5 sm:p-6 lg:col-span-6 flex flex-col justify-between" data-purpose="top-books-podium">
<div class="flex items-start justify-between gap-3 mb-4">
<div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">Buku Pilihan Kurator</h3>
<p class="text-xs text-brand-muted font-medium">Urutan kurasi dari koleksi unggulan katalog</p>
</div>
<RouterLink class="neo-pill bg-brand-yellow hover:bg-[#eab035] px-3 py-1 text-xs font-bold" to="/katalog">
            +Semua
          </RouterLink>
</div>
<!-- 6 Stylized Ranking Bars matching Reference exact colors & structure -->
<div class="pt-8 sm:pt-10 pb-2">
<div v-if="podiumBooks.length" class="h-44 flex items-end justify-between gap-2 px-1">
<div v-for="(item, i) in podiumBooks" :key="item.book.id" class="flex-1 flex flex-col items-center gap-1.5 h-full justify-end">
<div class="w-8 h-8 rounded-full border-2 border-brand-dark text-xs font-black flex items-center justify-center shadow-sm" :class="podiumStyles[i].avatar">
                {{ item.podium.emoji }}
              </div>
<span class="text-[10px] font-bold text-brand-muted truncate max-w-[50px]">{{ item.podium.short }}</span>
<div class="w-full rounded-2xl border-2 border-brand-dark flex flex-col items-center justify-start pt-2" :class="podiumStyles[i].bar" :style="{ height: podiumStyles[i].height }">
<span class="text-xs font-black text-brand-dark">#{{ i + 1 }}</span>
</div>
</div>
</div>
<div v-else class="h-44 flex items-center justify-center text-xs font-bold text-brand-muted">
        Memuat peringkat…
      </div>
</div>
<div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-1.5 text-xs font-bold text-brand-muted">
<span>Diperbarui saat kurator menambah koleksi</span>
<span class="text-brand-dark">Koleksi domain publik &amp; lisensi terbuka</span>
</div>
</div>
<!-- Right Card: Author Rankings & Community Highlights -->
<div class="neo-card p-5 sm:p-6 lg:col-span-6 flex flex-col justify-between" data-purpose="top-authors-list">
<div>
<div class="flex items-start justify-between gap-3 mb-4">
<div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">Penulis Terfavorit</h3>
<p class="text-xs text-brand-muted font-medium">Peringkat kurator berdasarkan koleksi yang tersedia</p>
</div>
<span class="neo-pill bg-brand-green-light px-3 py-1 text-xs font-bold text-brand-dark">
              Komunitas
            </span>
</div>
<!-- Authors list with rank avatars -->
<div v-if="favoriteAuthors.length" class="space-y-3 mt-4">
<div v-for="(a, i) in favoriteAuthors" :key="a.id" class="flex items-center justify-between gap-2 p-2.5 rounded-xl border-1.5 border-brand-dark bg-white hover:bg-gray-50">
<div class="flex items-center gap-3 min-w-0">
<div class="w-9 h-9 shrink-0 rounded-full border-2 border-brand-dark flex items-center justify-center font-extrabold text-sm text-brand-dark" :class="a.avatar_class">
                  {{ a.initials }}
                </div>
<div class="min-w-0">
<h4 class="font-bold text-sm text-brand-dark truncate">{{ a.name }}</h4>
<p class="text-[11px] text-brand-muted font-medium truncate">{{ a.line }}</p>
</div>
</div>
<span class="neo-pill shrink-0 text-brand-dark text-xs px-2.5 py-1" :class="authorPillClasses[i % authorPillClasses.length]">Top #{{ i + 1 }}</span>
</div>
</div>
<div v-else class="space-y-3 mt-4 text-center text-xs font-bold text-brand-muted py-6">
  Memuat penulis favorit…
</div>
</div>
<div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-1.5">
<RouterLink class="text-xs font-bold text-brand-dark hover:underline" to="/katalog">
            Jelajahi katalog lengkap →
          </RouterLink>
<span class="text-xs font-semibold text-brand-muted">Arsip berlisensi terbuka</span>
</div>
</div>
</section>
<!-- END: TopRankingsSection -->
<!-- BEGIN: CategoryGridSection -->
<!-- Grid of category cards with pastel colors -->
<section class="space-y-6" data-purpose="browse-by-category" id="kategori">
<div>
<span class="neo-pill bg-brand-blue-light px-3.5 py-1 text-xs font-bold text-brand-dark mb-1">
          🗂️ Jelajahi Menurut Minat
        </span>
<h2 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark tracking-tight">
          Koleksi Beragam Sesuai Selera
        </h2>
<p class="text-sm text-brand-muted font-medium mt-1">
          Telusuri kategori yang tersedia di katalog. Setiap judul telah melewati pemeriksaan status lisensi sebelum ditayangkan.
        </p>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-5">
<RouterLink v-for="(category, i) in categories" :key="category.slug" class="neo-card p-4 sm:p-5 flex flex-col justify-between group" :class="categoryStyles[i % categoryStyles.length].card" :to="{ path: '/katalog', query: { kategori: category.slug } }">
<div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl border-2 border-brand-dark bg-white flex items-center justify-center text-xl sm:text-2xl group-hover:scale-105 transition-transform">
            {{ categoryStyles[i % categoryStyles.length].emoji }}
          </div>
<div class="mt-3 sm:mt-4">
<h3 class="font-bold text-sm sm:text-base text-brand-dark leading-tight">{{ category.name }}</h3>
<p class="text-[11px] sm:text-xs text-brand-muted font-semibold mt-1">{{ category.books_count }} naskah</p>
</div>
</RouterLink>
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
            Kategori pilihan dari katalog terbuka libfree, lengkap dengan jumlah judul yang sudah terverifikasi lisensinya.
          </p>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<!-- Special Collection Card 1 -->
<div class="neo-card p-5 sm:p-6 flex items-center justify-between gap-3 bg-white hover:-translate-y-0.5">
<div class="space-y-2 pr-4">
<span class="neo-pill bg-brand-coral-light text-brand-dark px-3 py-0.5 text-xs font-bold">Koleksi Abadi</span>
<h3 class="font-display font-extrabold text-lg sm:text-xl text-brand-dark leading-snug">
              Sastra Klasik Nusantara
            </h3>
<p class="text-xs text-brand-muted font-medium leading-relaxed">
              Karya sastra pra-kemerdekaan hingga modern awal, dari Max Havelaar sampai sastra Melayu klasik.
            </p>
<div class="pt-2">
<RouterLink class="text-xs font-extrabold text-brand-dark underline hover:text-brand-coral" :to="{ path: '/katalog', query: { kategori: 'sastra-klasik' } }">
                Jelajahi {{ classicCount }} Judul Klasik →
              </RouterLink>
</div>
</div>
<!-- Circular Graphic Emblem (Like the "09:30" watch face in reference) -->
<div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full border-2 border-brand-dark bg-[#2C3E50] p-1.5 shrink-0 flex items-center justify-center relative overflow-hidden shadow-sm">
<div class="w-full h-full rounded-full border border-dashed border-brand-yellow flex flex-col items-center justify-center text-center p-1">
<span class="text-lg">📜</span>
<span class="text-[9px] font-black text-brand-yellow tracking-widest uppercase">1920-50</span>
</div>
</div>
</div>
<!-- Special Collection Card 2 -->
<div class="neo-card p-5 sm:p-6 flex items-center justify-between gap-3 bg-white hover:-translate-y-0.5">
<div class="space-y-2 pr-4">
<span class="neo-pill bg-brand-green-light text-brand-dark px-3 py-0.5 text-xs font-bold">Edisi Naskah</span>
<h3 class="font-display font-extrabold text-lg sm:text-xl text-brand-dark leading-snug">
              Cerita Rakyat &amp; Mitos
            </h3>
<p class="text-xs text-brand-muted font-medium leading-relaxed">
              Dongeng, legenda, dan hikmah lisan nusantara yang tercatat dalam naskah lama.
            </p>
<div class="pt-2">
<RouterLink class="text-xs font-extrabold text-brand-dark underline hover:text-brand-green" :to="{ path: '/katalog', query: { kategori: 'cerita-rakyat-mitos' } }">
                Buka {{ folkloreCount }} Judul Cerita Rakyat →
              </RouterLink>
</div>
</div>
<!-- Circular Graphic Emblem -->
<div class="w-16 h-16 sm:w-24 sm:h-24 rounded-full border-2 border-brand-dark bg-brand-yellow p-1.5 shrink-0 flex items-center justify-center relative overflow-hidden shadow-sm">
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
            Rak Baca Lintas Perangkat
          </h3>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Simpan judul favorit ke rak baca, lanjutkan bab terakhir, dan akses ulang dari perangkat mana pun selama akunmu aktif.
          </p>
<div class="pt-2">
<span class="text-xs font-bold text-brand-dark">Rak pribadi →</span>
</div>
</div>
<!-- Benefit 3 -->
<div class="neo-card p-6 bg-white space-y-4 text-left">
<div class="w-12 h-12 rounded-2xl border-2 border-brand-dark bg-brand-green flex items-center justify-center text-xl text-white font-black shadow-sm">
            03
          </div>
<h3 class="font-display font-extrabold text-xl text-brand-dark">
            Arsip &amp; Kontribusi Terbuka
          </h3>
<p class="text-sm text-brand-muted font-medium leading-relaxed">
            Setiap judul melewati audit lisensi sebelum tayang. Pembaca dapat mengusulkan naskah domain publik atau berlisensi terbuka lengkap dengan sumbernya.
          </p>
<div class="pt-2">
<span class="text-xs font-bold text-brand-green">Audit lisensi terbuka →</span>
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
            Bantu pembaca lain menemukan karya klasik yang bebas hak cipta. Kirim naskah beserta sumber dan bukti lisensinya untuk ditinjau kurator.
          </p>
</div>
<!-- CTA Action Buttons -->
<div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
<RouterLink class="neo-pill bg-brand-yellow hover:bg-[#ebb035] text-brand-dark px-7 py-3 text-sm sm:text-base w-full sm:w-auto shadow-sm text-center" to="/unggah">
            Unggah Buku Sekarang 📚
          </RouterLink>
<RouterLink class="neo-pill bg-white hover:bg-gray-100 text-brand-dark px-7 py-3 text-sm sm:text-base w-full sm:w-auto text-center" to="/lisensi#panduan">
            Panduan Hak Cipta
          </RouterLink>
</div>
<div class="pt-2 flex flex-wrap items-center justify-center gap-6 text-xs text-brand-muted font-semibold">
<span>✓ Domain publik &amp; lisensi terbuka</span>
<span>✓ Ditinjau kurator sebelum tayang</span>
<span>✓ Metadata terstandar</span>
</div>
</div>
</section>
<!-- END: ContributionBanner -->
<!-- BEGIN: FAQSection -->
<!-- Frequently Asked Questions with clean 2px outlines -->
<section class="space-y-6 max-w-3xl mx-auto" data-purpose="faq-section" id="faq">
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
            Ya, sepenuhnya gratis. Kami tidak mengenakan biaya langganan, tidak membatasi kuota baca bulanan, dan tidak menyisipkan iklan yang mengganggu kenyamanan membaca. Layanan dikelola secara mandiri oleh tim kecil; biaya operasional ditanggung penyelenggara.
          </p>
</details>
<!-- FAQ 2 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Format apa saja yang tersedia untuk diunduh?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Format unduhan mengikuti berkas yang dilampirkan kurator, umumnya <strong>PDF</strong> atau <strong>EPUB</strong>. Setiap buku juga memiliki cuplikan baca langsung di peramban; bila naskah digital belum tersedia, tombol unduh tidak ditampilkan dan halaman detail akan menjelaskan statusnya.
          </p>
</details>
<!-- FAQ 3 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Bagaimana cara berkontribusi atau mengunggah buku?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Daftarkan akun pembaca, lalu kirim naskah melalui halaman "Unggah Naskah" beserta berkas PDF/EPUB, tautan sumber, dan status lisensinya. Kurator akan memeriksa kelengkapan sebelum naskah dapat tayang.
          </p>
</details>
<!-- FAQ 4 -->
<details class="neo-card p-5 group bg-white open:bg-brand-yellow-light/30 transition-colors">
<summary class="font-extrabold text-base text-brand-dark cursor-pointer list-none flex items-center justify-between">
<span>Apakah progres bacaan dan buku simpanan tersimpan?</span>
<span class="w-6 h-6 rounded-full border border-brand-dark bg-brand-yellow flex items-center justify-center text-xs group-open:rotate-180 transition-transform">▼</span>
</summary>
<p class="mt-3 text-sm text-brand-muted font-medium leading-relaxed pt-2 border-t border-brand-dark/10">
            Ya. Buku yang kamu simpan tersimpan di rak baca akun, sedangkan bab terakhir yang dibuka dicatat di peramban perangkat tersebut. Kamu juga dapat menulis ulasan pada halaman detail buku setelah masuk.
          </p>
</details>
</div>
</section>
<!-- END: FAQSection -->
</main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const searchInput = ref(null)
const searchQuery = ref('')
const books = ref([])
const categories = ref([])
const favoriteAuthors = ref([])
const heroIndex = ref(0)

const classicCount = computed(() => categories.value.find((category) => category.slug === 'sastra-klasik')?.books_count ?? 0)
const folkloreCount = computed(() => categories.value.find((category) => category.slug === 'cerita-rakyat-mitos')?.books_count ?? 0)

const categoryStyles = [
  { emoji: '📚', card: 'bg-brand-coral-light/70 hover:bg-brand-coral-light' },
  { emoji: '🏛️', card: 'bg-brand-yellow-light/80 hover:bg-brand-yellow-light' },
  { emoji: '🔬', card: 'bg-brand-blue-light/70 hover:bg-brand-blue-light' },
  { emoji: '🌱', card: 'bg-brand-green-light/70 hover:bg-brand-green-light' },
  { emoji: '🕊️', card: 'bg-brand-lavender/70 hover:bg-brand-lavender' },
  { emoji: '📜', card: 'bg-brand-peach/70 hover:bg-brand-peach' },
]

// Quick-search labels forwarded to the catalog (M1.8).
const quickTags = ['Multatuli', 'Sejarah Nusantara', 'Sains Populer', 'Filsafat', 'Cerita Rakyat']

// Podium palette from the reference image (avatar bubble + bar color + bar height).
const podiumStyles = [
  { avatar: 'bg-brand-yellow', bar: 'bg-brand-green', height: '9rem' },
  { avatar: 'bg-brand-coral-light', bar: 'bg-brand-coral', height: '7rem' },
  { avatar: 'bg-brand-yellow-light', bar: 'bg-brand-yellow', height: '6rem' },
  { avatar: 'bg-brand-blue-light', bar: 'bg-brand-blue', height: '5rem' },
  { avatar: 'bg-brand-green-light', bar: 'bg-brand-green', height: '4rem' },
  { avatar: 'bg-brand-peach', bar: 'bg-brand-coral', height: '3rem' },
]

// Rank pill colors for the favorite authors list.
const authorPillClasses = ['bg-brand-green', 'bg-brand-coral-light', 'bg-brand-blue-light', 'bg-gray-100']

const heroSlides = computed(() =>
  books.value
    .filter((b) => b.showcase?.hero)
    .slice(0, 4)
    .map((b) => ({ book: b, hero: b.showcase.hero })),
)

const activeHero = computed(() => heroSlides.value[heroIndex.value] ?? null)

const featuredBooks = computed(() =>
  books.value
    .filter((b) => b.is_featured && b.showcase?.featured)
    .sort((a, b) => (a.featured_rank ?? 99) - (b.featured_rank ?? 99))
    .slice(0, 4)
    .map((b) => ({ book: b, featured: b.showcase.featured })),
)

const podiumBooks = computed(() =>
  books.value
    .filter((b) => b.showcase?.podium)
    .slice(0, 6)
    .map((b) => ({ book: b, podium: b.showcase.podium })),
)

function splitRating(label) {
  const match = /^([\d.]+)\s*\((.+)\)$/.exec(label ?? '')
  return match ? { value: match[1], count: match[2] } : { value: label ?? '', count: '' }
}

function prevHero() {
  if (!heroSlides.value.length) return
  heroIndex.value = (heroIndex.value - 1 + heroSlides.value.length) % heroSlides.value.length
}

function nextHero() {
  if (!heroSlides.value.length) return
  heroIndex.value = (heroIndex.value + 1) % heroSlides.value.length
}

function goSearch() {
  const q = searchQuery.value.trim()
  router.push(q ? { path: '/katalog', query: { q } } : { path: '/katalog' })
}

async function fetchBooks() {
  try {
    const response = await fetch('/api/buku?per_page=100')
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    books.value = payload.data
  } catch {
    books.value = []
  }
}

async function fetchAuthors() {
  try {
    const response = await fetch('/api/penulis?favorit=1')
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    favoriteAuthors.value = payload.data.slice(0, 4)
  } catch {
    favoriteAuthors.value = []
  }
}

async function fetchCategories() {
  try {
    const response = await fetch('/api/kategori')
    if (!response.ok) throw new Error('HTTP ' + response.status)

    const payload = await response.json()
    categories.value = payload.data
  } catch {
    categories.value = []
  }
}

onMounted(() => {
  fetchBooks()
  fetchCategories()
  fetchAuthors()
})
</script>
