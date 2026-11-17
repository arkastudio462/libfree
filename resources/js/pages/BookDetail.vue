<template>
<main class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-20 pb-12 lg:pb-16">
<!-- Loading state -->
<div v-if="loading" class="neo-card p-10 bg-white border-2 border-brand-dark shadow-solid text-center text-sm font-bold text-brand-muted">
    Memuat detail buku…
  </div>
<!-- Error / 404 state -->
<div v-else-if="error" class="neo-card p-10 bg-white border-2 border-brand-dark shadow-solid text-center space-y-3">
<span class="material-symbols-outlined text-4xl text-brand-muted">search_off</span>
<p class="text-sm font-bold text-brand-dark">{{ error }}</p>
<div class="flex items-center justify-center gap-2">
<button class="neo-pill px-4 py-2 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-bold border-2 border-brand-dark" type="button" @click="fetchBook">Coba Lagi</button>
<RouterLink class="neo-pill px-4 py-2 bg-white hover:bg-neutral-100 text-brand-dark text-xs font-bold border-2 border-brand-dark" to="/katalog">
          Kembali ke Katalog
        </RouterLink>
</div>
</div>
<div v-else-if="book" class="flex flex-col w-full space-y-10 sm:space-y-12">
<!-- 1. BREADCRUMB & HEADER NAVIGASI -->
<section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<nav aria-label="Breadcrumb" class="flex items-center gap-2 text-xs sm:text-sm font-bold flex-wrap">
<RouterLink class="text-neutral-500 hover:text-brand-dark transition-colors flex items-center gap-1" to="/">
<span class="material-symbols-outlined text-base">home</span> Beranda
      </RouterLink>
<span class="text-neutral-400">/</span>
<RouterLink class="text-neutral-500 hover:text-brand-dark transition-colors" to="/katalog">Katalog &amp; Koleksi</RouterLink>
<span class="text-neutral-400">/</span>
<span class="px-2.5 py-0.5 rounded-full bg-brand-coral/20 text-brand-dark border-2 border-brand-dark text-xs">{{ book.category?.name }}</span>
<span class="text-neutral-400">/</span>
<span class="text-brand-dark font-extrabold">{{ book.title }}</span>
</nav>
<div class="flex flex-wrap items-center gap-2 sm:gap-2.5 shrink-0">
<RouterLink class="neo-pill px-3.5 py-1.5 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid" to="/katalog">
<span class="material-symbols-outlined text-sm mr-1">arrow_back</span> Kembali ke Katalog
      </RouterLink>
<button class="neo-pill px-3.5 py-1.5 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid" title="Bagikan Buku" type="button" @click="shareBook">
<span class="material-symbols-outlined text-sm mr-1">share</span> Bagikan
      </button>
<button class="neo-pill px-3.5 py-1.5 text-xs shadow-solid flex items-center gap-1.5 cursor-pointer disabled:opacity-60" :class="bookmarked ? 'bg-brand-coral hover:bg-[#f3b8b8]' : 'bg-brand-yellow hover:bg-[#ebae34]'" type="button" :aria-pressed="bookmarked" :disabled="bookmarkBusy" @click="toggleBookmark">
<span class="material-symbols-outlined text-sm" :style="fillOn">bookmark</span>
<span>{{ bookmarked ? 'Tersimpan' : 'Simpan' }}</span>
<span class="bg-brand-dark text-white rounded-full px-2  text-[10px] ml-0.5">{{ numberFormat.format(bookmarkCount) }}</span>
</button>
</div>
<p v-if="bookmarkError" class="w-full text-[11px] font-bold text-brand-coral text-right" role="alert">{{ bookmarkError }}</p>
</section>
<!-- 2. HERO SECTION DETAIL BUKU (2 KOLOM LAYOUT) -->
<section class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- KOLOM KIRI (Visual Sampul, Aksi Cepat Unduh/Baca, Lisensi) -->
<div class="lg:col-span-5 space-y-6">
<div class="neo-card p-6 bg-white shadow-solid-lg flex flex-col items-center relative overflow-hidden">
<!-- Banner Strip Sudut -->
<div v-if="detail.banner" class="absolute -right-12 top-6 border-2 border-brand-dark text-brand-dark text-[11px] font-extrabold uppercase px-12 py-1 rotate-45 shadow-sm" :class="detail.banner_class">
          {{ detail.banner }}
        </div>
<!-- Frame Sampul Buku -->
<div class="w-full max-w-xs aspect-[3/4.2] neo-card border-2 border-brand-dark p-6 relative flex flex-col justify-between shadow-solid overflow-hidden group" :style="{ backgroundColor: cover.bg }">
<div v-if="cover.image" class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-multiply group-hover:scale-105 transition-transform duration-500" :style="{ backgroundImage: 'url(' + cover.image + ')' }"></div>
<!-- Bagian Atas Sampul -->
<div v-if="cover.series_badge || cover.genre" class="relative z-10 space-y-1">
<span v-if="cover.series_badge" class="neo-pill px-2.5 py-0.5 text-[10px] bg-brand-dark text-white font-extrabold tracking-wider uppercase">
              {{ cover.series_badge }}
            </span>
<p v-if="cover.genre" class="text-[11px] font-bold tracking-widest text-brand-dark/70 uppercase pt-2">{{ cover.genre }}</p>
</div>
<!-- Judul Sampul -->
<div class="relative z-10 py-4 my-auto">
<h1 class="font-display font-extrabold text-3xl sm:text-4xl text-brand-dark tracking-tight leading-none sm:leading-10 mb-1">
              {{ cover.title }}<br v-if="cover.title_accent"><span v-if="cover.title_accent" class="text-brand-coral">{{ cover.title_accent }}</span>
</h1>
<div class="w-16 h-1.5 bg-brand-yellow border border-brand-dark my-3"></div>
<p class="font-sans font-bold text-xs uppercase tracking-wider text-neutral-800">
              {{ cover.author }}
            </p>
</div>
<!-- Bagian Bawah Sampul -->
<div class="relative z-10 flex items-center justify-between pt-2 border-t-2 border-brand-dark/20 text-[10px] font-bold text-neutral-700">
<span>{{ cover.footer_left }}</span>
<span class="flex items-center gap-1 text-brand-green">
<span class="material-symbols-outlined text-xs" :style="fillOn">verified</span> {{ cover.footer_right }}
            </span>
</div>
</div>
<!-- Badges di Bawah Sampul -->
<div class="flex flex-wrap items-center justify-center gap-2 mt-4">
<span v-for="badge in detail.cover_badges" :key="badge.label" class="neo-pill px-3 py-1 text-xs text-brand-dark" :class="badge.class">
<span class="material-symbols-outlined text-xs mr-1" :class="badge.icon_class" :style="badge.fill ? fillOn : null">{{ badge.icon }}</span> {{ badge.label }}
          </span>
</div>
</div>
<!-- Panel Aksi Baca & Unduh -->
<div class="neo-card p-5 bg-white shadow-solid space-y-4">
<RouterLink class="neo-pill w-full py-3.5 bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-sm sm:text-base font-extrabold text-center shadow-solid flex items-center justify-center gap-2 group" :to="'/baca/' + book.slug">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">menu_book</span>
          {{ readerUnlocked ? 'Lanjutkan Membaca' : 'Baca Sekarang di Web' }}
          <span class="material-symbols-outlined text-lg">arrow_forward</span>
</RouterLink>
<div v-if="detail.downloads.length" class="grid grid-cols-2 gap-2.5 pt-1">
<a v-for="dl in detail.downloads" :key="dl.label" class="neo-pill py-2.5 px-3 text-brand-dark text-xs font-bold text-center justify-center" :class="dl.class" :href="dl.url || '#'">
<span class="material-symbols-outlined text-sm mr-1">{{ dl.icon }}</span> {{ dl.label }}
          </a>
</div>
<!-- Status Lisensi & Naskah -->
<div class="rounded-xl p-3.5 border-2 border-brand-dark space-y-2 text-xs" :class="detail.license?.verified ? 'bg-brand-green-light' : 'bg-brand-yellow-light'">
<div class="flex items-start gap-2 text-brand-dark font-bold">
<span class="material-symbols-outlined text-base shrink-0" :class="detail.license?.verified ? 'text-brand-green' : 'text-brand-coral'" :style="fillOn">{{ detail.license?.verified ? 'verified_user' : 'info' }}</span>
<span>{{ detail.license?.name || 'Lisensi belum diverifikasi' }}<template v-if="detail.license?.verified"> — terverifikasi untuk publikasi terbuka.</template></span>
</div>
<div v-if="detail.license?.source_url" class="flex items-start gap-2 text-neutral-700 font-medium pl-6">
<span class="material-symbols-outlined text-brand-green text-xs shrink-0">link</span>
<a class="underline decoration-brand-yellow decoration-2 break-all hover:text-brand-dark" :href="detail.license.source_url" rel="noopener noreferrer" target="_blank">Sumber naskah</a>
</div>
<div v-if="detail.license?.audited_at" class="flex items-center gap-2 text-neutral-700 font-medium pl-6">
<span class="material-symbols-outlined text-brand-green text-xs">done_all</span>
<span>Terakhir diaudit {{ formatDate(detail.license.audited_at) }}.</span>
</div>
</div>
</div>
</div>
<!-- KOLOM KANAN (Informasi Utama, Statistik, Tab Sinopsis, Spesifikasi) -->
<div class="lg:col-span-7 space-y-6">
<div class="neo-card p-6 sm:p-8 bg-white shadow-solid-lg space-y-6">
<!-- Badges Kategori -->
<div class="flex flex-wrap items-center gap-2">
<span v-for="cb in detail.category_badges" :key="cb.label" class="neo-pill px-3 py-1 text-xs text-brand-dark" :class="cb.class">{{ cb.label }}</span>
</div>
<!-- Judul & Subjudul -->
<div class="space-y-2">
<h2 class="font-display font-extrabold text-3xl sm:text-4xl lg:text-5xl text-brand-dark leading-tight sm:leading-10 lg:leading-none tracking-tight">
            {{ book.title }}
          </h2>
<p v-if="detail.subtitle" class="font-sans font-bold text-base sm:text-lg text-brand-muted">
            {{ detail.subtitle }}
          </p>
</div>
<!-- Profil Singkat Penulis -->
<div v-if="book.author" class="flex flex-wrap items-center gap-3 sm:gap-3.5 p-3 rounded-2xl bg-brand-yellow-light border-2 border-brand-dark">
<div class="w-12 h-12 rounded-full border-2 border-brand-dark overflow-hidden shrink-0 bg-neutral-200 flex items-center justify-center font-extrabold text-sm text-brand-dark" :class="book.author.avatar_class" :style="book.author.photo_url ? { backgroundImage: 'url(' + book.author.photo_url + ')', backgroundSize: 'cover', backgroundPosition: 'center' } : null">
<span v-if="!book.author.photo_url">{{ book.author.initials }}</span>
</div>
<div class="flex-1 min-w-0">
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-base text-brand-dark truncate">{{ book.author.name }}</span>
<span class="material-symbols-outlined text-brand-blue text-sm" :style="fillOn" title="Penulis Legendaris Terverifikasi">verified</span>
</div>
<p v-if="book.author.subtitle" class="text-xs text-neutral-600 font-semibold truncate">{{ book.author.subtitle }}</p>
</div>
<a class="neo-pill px-3 py-1.5 text-xs bg-white hover:bg-neutral-50 text-brand-dark shrink-0" href="#penulis-section">
            Biografi
          </a>
</div>
<!-- Metrik & Rating Interaktif -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
<div v-for="m in detail.metrics" :key="m.label" class="neo-card p-3 bg-brand-cream border-2 border-brand-dark text-center">
<div class="flex items-center justify-center gap-1 text-sm font-extrabold" :class="m.icon_class">
<span class="material-symbols-outlined text-base" :style="m.fill ? fillOn : null">{{ m.icon }}</span>
<span class="text-brand-dark text-base">{{ m.value }}</span>
</div>
<span class="text-[11px] font-bold text-neutral-600 block mt-0.5">{{ m.label }}</span>
</div>
</div>
<!-- Tab Navigasi Sinopsis & Konteks -->
<div class="space-y-4 pt-2">
<div class="flex items-center gap-2 border-b-2 border-brand-dark/15 pb-2 overflow-x-auto">
<button v-for="(tab, i) in detail.tabs" :key="tab.label" class="tab-btn neo-pill px-4 py-1.5 text-xs cursor-pointer" :class="activeTab === i ? 'bg-brand-dark text-white' : 'bg-white text-brand-dark hover:bg-neutral-100'" type="button" @click="activeTab = i">{{ tab.label }}</button>
</div>
<!-- Konten Tab -->
<div v-for="(tab, i) in detail.tabs" v-show="activeTab === i" :key="tab.label" class="tab-pane space-y-3 text-sm text-neutral-800 leading-relaxed font-medium" v-html="tab.html"></div>
</div>
<!-- Spesifikasi & Metadata Grid Mini -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t-2 border-brand-dark/10 text-xs">
<div v-for="s in detail.specs" :key="s.label">
<span class="text-neutral-500 font-bold block">{{ s.label }}</span>
<span class="font-extrabold text-brand-dark">{{ s.value }}</span>
</div>
</div>
</div>
</div>
</section>
<!-- 3. FITUR INTERAKTIF BACA & INTISARI -->
<section class="grid grid-cols-1 md:grid-cols-12 gap-6 w-full items-stretch">
  <!-- Kartu Kutipan Populer (7 Kolom) -->
  <div v-if="book.quote" class="md:col-span-7 neo-card p-6 sm:p-8 bg-brand-yellow-light/60 border-2 border-brand-dark shadow-solid rounded-2xl flex flex-col justify-between relative overflow-hidden">
    <div class="space-y-4 relative z-10">
      <div class="flex items-center justify-between gap-3 flex-wrap">
        <span class="neo-pill px-3 py-1 text-xs bg-brand-coral text-brand-dark font-extrabold uppercase border-2 border-brand-dark shadow-sm">
          {{ detail.quote_label || 'Kutipan Pilihan' }}
        </span>
        <button class="neo-pill px-3 py-1.5 text-xs flex items-center gap-1.5 shadow-sm border-2 border-brand-dark transition-colors cursor-pointer" :class="copied ? 'bg-brand-green text-white' : 'bg-white hover:bg-neutral-100 text-brand-dark'" type="button" @click="copyQuote">
          <span class="material-symbols-outlined text-xs">content_copy</span>
          <span class="font-bold">{{ copied ? 'Tersalin!' : 'Salin Kutipan' }}</span>
        </button>
      </div>
      <div class="pt-2">
        <span class="text-4xl sm:text-5xl font-serif text-brand-dark/20 font-bold block leading-none select-none -mb-3">“</span>
        <blockquote class="font-display font-extrabold text-lg sm:text-xl md:text-2xl text-brand-dark leading-snug sm:leading-7 md:leading-8 tracking-tight pl-2">
          “{{ book.quote }}”
        </blockquote>
      </div>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-6 mt-4 border-t-2 border-brand-dark/15 text-xs font-bold text-neutral-800 relative z-10">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-coral border border-brand-dark"></span>
        <span class="font-extrabold text-sm text-brand-dark">{{ book.quote_source }}</span>
        <span v-if="book.quote_source_role" class="text-neutral-500 font-medium text-xs hidden sm:inline">• {{ book.quote_source_role }}</span>
      </div>
      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border-2 border-brand-dark text-neutral-700 shadow-sm">
        <span class="material-symbols-outlined text-sm text-brand-coral" :style="fillOn">favorite</span>
        <span>{{ detail.quote_count }}</span>
      </div>
    </div>
  </div>

  <!-- Kartu Distribusi Nilai Pembaca -->
  <div class="neo-card p-6 sm:p-8 bg-white border-2 border-brand-dark shadow-solid rounded-2xl flex flex-col justify-between" :class="book.quote ? 'md:col-span-5' : 'md:col-span-12'">
    <div>
      <div class="flex items-start justify-between pb-4 border-b-2 border-brand-dark/10">
        <div>
          <h4 class="font-display font-extrabold text-base sm:text-lg text-brand-dark leading-tight sm:leading-7">Distribusi Nilai</h4>
          <p class="text-xs text-neutral-500 font-bold mt-0.5">{{ detail.distribution.based_on }}</p>
        </div>
        <div class="text-right shrink-0">
          <div class="flex items-center justify-end gap-1.5">
            <span class="material-symbols-outlined text-brand-yellow text-xl" :style="fillOn">star</span>
            <span class="font-display font-extrabold text-2xl text-brand-dark leading-none">{{ detail.distribution.rating }}</span>
          </div>
          <span class="text-[11px] font-bold text-neutral-500 block mt-1">Skala 5.0</span>
        </div>
      </div>

      <!-- Breakdown Bar Nilai 5 ke 1 -->
      <div class="space-y-2.5 pt-4 text-xs font-bold">
        <div v-for="bar in detail.distribution.bars" :key="bar.stars" class="flex items-center gap-2.5">
          <span class="w-16 shrink-0 flex items-center gap-1 text-neutral-700 font-extrabold">
            {{ bar.stars }} <span class="material-symbols-outlined text-xs text-brand-yellow" :style="fillOn">star</span>
          </span>
          <div class="flex-1 h-3.5 bg-neutral-100 rounded-full border-2 border-brand-dark overflow-hidden">
            <div class="h-full" :class="barClasses[bar.stars]" :style="{ width: bar.width + '%' }"></div>
          </div>
          <span class="w-10 text-right text-brand-dark font-extrabold">{{ bar.label }}</span>
        </div>
      </div>
    </div>

    <div class="pt-4 mt-2 border-t-2 border-brand-dark/10 flex items-center justify-between text-xs font-bold">
      <span class="text-brand-green flex items-center gap-1">
        <span class="material-symbols-outlined text-sm" :style="fillOn">verified</span> {{ detail.distribution.satisfaction }}
      </span>
      <a href="#reviews" class="text-brand-dark hover:underline flex items-center gap-0.5">
        <span>Lihat Semua Ulasan</span>
        <span class="material-symbols-outlined text-xs">arrow_forward</span>
      </a>
    </div>
  </div>
</section>
<!-- 4. DAFTAR ISI & STRUKTUR BAB -->

<!-- 5. TENTANG PENULIS & SERIAL -->
<section v-if="book.author && (book.author.bio || detail.series)" class="neo-card p-6 sm:p-8 bg-brand-cream shadow-solid-lg space-y-6" id="penulis-section">
<div class="flex flex-col md:flex-row items-start md:items-center gap-6">
<div class="w-24 h-24 sm:w-28 sm:h-28 rounded-3xl border-2 border-brand-dark bg-white overflow-hidden shrink-0 shadow-solid" :style="book.author.photo_url ? { backgroundImage: 'url(' + book.author.photo_url + ')', backgroundSize: 'cover', backgroundPosition: 'center' } : null"></div>
<div class="space-y-2 flex-1">
<div class="flex flex-wrap items-center gap-2">
<h3 class="font-display font-extrabold text-2xl sm:text-3xl text-brand-dark">{{ book.author.name }}</h3>
<span v-if="book.author.badge" class="neo-pill px-3 py-0.5 text-xs bg-brand-yellow text-brand-dark font-extrabold">{{ book.author.badge }}</span>
</div>
<p v-if="book.author.bio" class="text-xs sm:text-sm text-neutral-700 leading-relaxed sm:leading-5 font-medium">
          {{ book.author.bio }}
        </p>
</div>
</div>
<!-- Serial Lainnya -->
<div v-if="detail.series" class="space-y-3 pt-4 border-t-2 border-brand-dark/10">
<h4 class="font-display font-extrabold text-base text-brand-dark">{{ detail.series.title }}</h4>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
<template v-for="item in detail.series.items" :key="item.title">
<!-- Buku aktif saat ini -->
<div v-if="item.active" class="neo-card p-4 bg-brand-yellow-light border-2 border-brand-dark relative">
<span class="neo-pill px-2 py-0.5 text-[10px] bg-brand-dark text-white font-extrabold mb-2">{{ item.badge }}</span>
<h5 class="font-display font-extrabold text-base text-brand-dark">{{ item.title }}</h5>
<p class="text-xs text-neutral-600 mt-1">{{ item.desc }}</p>
</div>
<a v-else class="neo-card p-4 bg-white hover:bg-neutral-50 border-2 border-brand-dark group transition-transform hover:-translate-y-1" :href="item.url">
<span class="neo-pill px-2 py-0.5 text-[10px] text-brand-dark font-extrabold mb-2" :class="item.badge_class">{{ item.badge }}</span>
<h5 class="font-display font-extrabold text-base text-brand-dark" :class="item.hover_class">{{ item.title }}</h5>
<p class="text-xs text-neutral-600 mt-1">{{ item.desc }}</p>
</a>
</template>
</div>
</div>
</section>
<!-- 6. BUKU SERUPA / REKOMENDASI REDAKSI -->
<section v-if="book.related.length" class="space-y-6">
<div class="flex items-center justify-between">
<div>
<h3 class="font-display font-extrabold text-2xl text-brand-dark">{{ detail.related_heading.title }}</h3>
<p class="text-xs sm:text-sm text-brand-muted font-bold">{{ detail.related_heading.subtitle }}</p>
</div>
<RouterLink class="neo-pill px-4 py-2 text-xs bg-white hover:bg-neutral-100 text-brand-dark shadow-solid hidden sm:inline-flex" to="/katalog">
        Lihat Semua Klasik →
      </RouterLink>
</div>
<div class="grid grid-cols-2 gap-3 sm:gap-5">
<div v-for="r in book.related" :key="r.id" class="neo-card p-3 sm:p-5 bg-white shadow-solid flex flex-col justify-between group hover:-translate-y-1 transition-transform">
<div class="space-y-3">
<div class="w-full aspect-[4/5] rounded-xl border-2 border-brand-dark relative overflow-hidden flex items-end p-3" :class="r.card.related_cover_class || 'bg-white'" :style="r.card.related_image ? { backgroundImage: 'url(' + r.card.related_image + ')', backgroundSize: 'cover', backgroundPosition: 'center' } : null">
<span class="neo-pill px-2.5 py-0.5 text-[10px] bg-white text-brand-dark font-extrabold shadow-sm">{{ r.card.related_badge }}</span>
</div>
<div>
<div class="flex items-center gap-1 text-brand-yellow text-xs font-bold mb-1">
<span class="material-symbols-outlined text-sm" :style="fillOn">star</span> {{ r.rating }}
            </div>
<h4 class="font-display font-extrabold text-sm sm:text-base text-brand-dark leading-snug" :class="r.card.related_hover_class">
              {{ r.title }}
            </h4>
<p class="text-xs font-bold text-neutral-500 mt-0.5">{{ r.author?.short_name || r.author?.name }} ({{ r.year }})</p>
</div>
</div>
<RouterLink class="neo-pill w-full py-2 text-xs font-extrabold text-center mt-4" :class="r.card.related_button_class || 'bg-brand-cream'" :to="'/buku/' + r.slug">
          Buka Buku
        </RouterLink>
</div>
</div>
</section>
<!-- 7. KOMENTAR & ULASAN PEMBACA -->
<section id="reviews" class="neo-card p-6 sm:p-8 bg-white shadow-solid space-y-6">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
<div>
<h3 class="font-display font-extrabold text-2xl text-brand-dark">{{ detail.reviews_heading.title }}</h3>
<p class="text-xs sm:text-sm text-brand-muted font-bold">{{ detail.reviews_heading.subtitle }}</p>
</div>
<button class="neo-pill px-4 py-2 w-full sm:w-auto bg-brand-yellow hover:bg-[#ebb035] text-brand-dark text-xs font-extrabold shadow-solid cursor-pointer" type="button" @click="reviewFormOpen = !reviewFormOpen">
        + Tulis Ulasan Kamu
      </button>
</div>
<p v-if="reviewError" class="rounded-xl bg-brand-coral-light border-2 border-brand-dark px-3.5 py-2.5 text-xs font-bold text-brand-dark" role="alert">{{ reviewError }}</p>
<!-- Form Tulis Ulasan (Toggle via state) -->
<div v-show="reviewFormOpen" class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3">
<h4 class="font-display font-bold text-sm text-brand-dark">Kirimkan Ulasanmu untuk {{ book.title }}:</h4>
<div class="flex items-center gap-2 text-xs font-bold">
<span>Beri Nilai:</span>
<div class="flex text-brand-yellow cursor-pointer">
<span v-for="i in 5" :key="i" class="material-symbols-outlined text-lg" :style="i <= reviewRating ? fillOn : null" @click="reviewRating = i">star</span>
</div>
</div>
<textarea v-model="reviewText" class="w-full neo-card p-3 text-xs sm:text-sm border-2 border-brand-dark focus:ring-0" maxlength="2000" placeholder="Tuliskan analisis, kesan, atau bab yang paling berkesan bagimu..." rows="3"></textarea>
<p v-if="reviewSuccess" class="text-xs font-bold text-brand-dark bg-brand-green-light border-2 border-brand-dark rounded-xl px-3 py-2" role="status">{{ reviewSuccess }}</p>
<div class="flex justify-end gap-2">
<button class="neo-pill px-4 py-1.5 text-xs bg-white text-brand-dark cursor-pointer" type="button" @click="reviewFormOpen = false">Batal</button>
<button class="neo-pill px-4 py-1.5 text-xs bg-brand-green text-brand-dark cursor-pointer disabled:opacity-60" type="button" :disabled="reviewSubmitting" @click="submitReview">{{ reviewSubmitting ? 'Mengirim…' : 'Kirimkan Ulasan' }}</button>
</div>
</div>
<!-- Daftar Ulasan -->
<div v-if="book.reviews.length" class="space-y-4">
<div v-for="r in book.reviews" :key="r.id" class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark space-y-3">
<div class="flex items-start justify-between gap-3">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full border-2 border-brand-dark flex items-center justify-center font-extrabold text-sm text-brand-dark" :class="r.avatar_class">
              {{ r.initials }}
            </div>
<div>
<div class="flex items-center gap-1.5">
<span class="font-display font-extrabold text-sm text-brand-dark">{{ r.name }}</span>
<span class="neo-pill px-2 text-[10px] font-extrabold" :class="r.badge_class">
                  {{ r.badge_text }}
                </span>
<span v-if="r.is_mine" class="neo-pill px-2 text-[10px] font-extrabold bg-brand-green text-brand-dark">Ulasanmu</span>
</div>
<span class="text-[11px] font-semibold text-neutral-500">{{ r.meta }}</span>
</div>
</div>
<div class="flex text-brand-yellow">
<span v-for="(s, si) in starIcons(r.rating)" :key="si" class="material-symbols-outlined text-sm" :style="s.fill ? fillOn : null">{{ s.icon }}</span>
</div>
</div>
<p class="text-xs sm:text-sm text-neutral-800 leading-relaxed sm:leading-5 font-medium">
            {{ r.body }}
          </p>
<div class="flex items-center gap-4 text-xs font-bold text-neutral-600 pt-1">
<button class="flex items-center gap-1 cursor-pointer transition-colors disabled:opacity-60" :class="r.helpful ? 'text-brand-green' : 'hover:text-brand-dark'" type="button" :disabled="helpfulBusy[r.id]" :aria-pressed="Boolean(r.helpful)" @click="toggleHelpful(r)">
<span class="material-symbols-outlined text-sm" :style="r.helpful ? fillOn : null">thumb_up</span> {{ numberFormat.format(r.helpfuls || 0) }} Bermanfaat
            </button>
</div>
</div>
</div>
<!-- Empty state ulasan -->
<div v-else class="p-5 rounded-2xl bg-brand-cream border-2 border-brand-dark text-center space-y-2">
<span class="material-symbols-outlined text-4xl text-brand-muted">reviews</span>
<p class="text-sm font-bold text-brand-dark">Belum ada ulasan untuk buku ini.</p>
<p class="text-xs text-brand-muted font-semibold">Jadilah pembaca pertama yang berbagi catatan pemikiran.</p>
</div>
</section>

</div></main>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiFetch, errorMessage } from '../lib/api'
import { setSeo } from '../lib/seo'
import { auth } from '../stores/auth'

const route = useRoute()
const router = useRouter()

const book = ref(null)
const loading = ref(true)
const error = ref(null)

// Reactive replacements for the template's vanilla DOM handlers.
const activeTab = ref(0)
const bookmarked = ref(false)
const bookmarkBusy = ref(false)
const bookmarkError = ref('')
const copied = ref(false)
const reviewFormOpen = ref(false)
const reviewRating = ref(5)
const reviewText = ref('')
const reviewSubmitting = ref(false)
const reviewError = ref('')
const reviewSuccess = ref('')
const helpfulBusy = ref({})

const detail = computed(() => book.value?.detail ?? null)
const cover = computed(() => detail.value?.cover ?? null)
const bookmarkCount = computed(() => Number(book.value?.bookmark_count ?? 0))
const readerUnlocked = computed(() => Boolean(book.value?.reader_available))

const numberFormat = new Intl.NumberFormat('id-ID')

function formatDate(value) {
  if (!value) return '—'

  return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date(value))
}

const fillOn = { fontVariationSettings: '"FILL" 1' }

const barClasses = {
  5: 'bg-brand-yellow',
  4: 'bg-brand-coral',
  3: 'bg-brand-blue',
  2: 'bg-brand-green',
  1: 'bg-neutral-400',
}

function starIcons(rating) {
  const icons = []
  const full = Math.floor(rating)

  for (let i = 0; i < full; i++) icons.push({ icon: 'star', fill: true })
  if (rating - full >= 0.5) icons.push({ icon: 'star_half', fill: true })
  while (icons.length < 5) icons.push({ icon: 'star', fill: false })

  return icons
}

// --- Data loading --------------------------------------------------------
let fetchSeq = 0

function applyBookSeo(data) {
  const author = data.author?.name
  const title = author ? `${data.title} — ${author} | libfree` : `${data.title} — libfree`
  const canonical = window.location.origin + '/buku/' + data.slug
  const synopsis = String(data.synopsis ?? '')
  const description = synopsis.length > 155 ? synopsis.slice(0, 152) + '…' : synopsis
  const image = data.detail?.cover?.image || undefined

  const schema = {
    '@context': 'https://schema.org',
    '@type': 'Book',
    name: data.title,
    url: canonical,
    author: author ? { '@type': 'Person', name: author } : undefined,
    description: synopsis || undefined,
    inLanguage: 'id-ID',
    bookFormat: 'https://schema.org/EBook',
    isAccessibleForFree: true,
    image,
  }

  if (Number(data.reviews_count) > 0) {
    schema.aggregateRating = {
      '@type': 'AggregateRating',
      ratingValue: data.rating,
      reviewCount: data.reviews_count,
      bestRating: 5,
      worstRating: 1,
    }
  }

  setSeo({ title, description, canonical, type: 'book', image, jsonLd: [schema] })
}

async function fetchBook() {
  const seq = ++fetchSeq
  loading.value = true
  error.value = null

  try {
    const payload = await apiFetch('/api/buku/' + route.params.slug)
    if (seq !== fetchSeq) return

    book.value = payload.data
    activeTab.value = 0
    bookmarked.value = Boolean(payload.data.is_bookmarked)
    bookmarkError.value = ''
    copied.value = false
    reviewFormOpen.value = false
    reviewRating.value = 5
    reviewText.value = ''
    reviewError.value = ''
    reviewSuccess.value = ''
    applyBookSeo(payload.data)
  } catch (err) {
    if (seq !== fetchSeq) return
    book.value = null
    error.value = err.status === 404
      ? 'Buku tidak ditemukan di katalog libfree.'
      : 'Gagal memuat detail buku. Silakan coba lagi.'

    setSeo({
      title: 'Buku tidak ditemukan — libfree',
      description: 'Judul yang kamu cari tidak tersedia di katalog libfree.',
      canonical: null,
      robots: 'noindex,nofollow',
      jsonLd: [],
    })
  } finally {
    if (seq === fetchSeq) loading.value = false
  }
}

// --- Actions -------------------------------------------------------------
async function toggleBookmark() {
  if (!book.value || bookmarkBusy.value) return

  if (!auth.user) {
    await router.push({ name: 'masuk', query: { redirect: route.fullPath } })
    return
  }

  bookmarkBusy.value = true
  bookmarkError.value = ''

  try {
    const payload = await apiFetch('/api/buku/' + book.value.slug + '/bookmark', {
      method: bookmarked.value ? 'DELETE' : 'POST',
    })

    bookmarked.value = payload.data.bookmarked
    book.value.bookmark_count = payload.data.bookmark_count
  } catch (caught) {
    bookmarkError.value = errorMessage(caught)
  } finally {
    bookmarkBusy.value = false
  }
}

async function copyQuote() {
  if (!book.value?.quote) return

  try {
    await navigator.clipboard.writeText(book.value.quote)
    copied.value = true
    setTimeout(() => {
      copied.value = false
    }, 2000)
  } catch {
    // Clipboard unavailable (insecure context) — ignore silently.
  }
}

function shareBook() {
  const data = {
    title: book.value.title + (book.value.author ? ' - ' + book.value.author.name : '') + ' | libfree',
    text: 'Baca karya ' + book.value.title + ' secara gratis dan legal di libfree.',
    url: window.location.href,
  }

  if (navigator.share) {
    navigator.share(data).catch(() => {})
  } else {
    navigator.clipboard.writeText(window.location.href)
    alert('Tautan halaman buku telah disalin ke papan klip!')
  }
}

async function syncBook() {
  try {
    const payload = await apiFetch('/api/buku/' + route.params.slug)
    if (book.value?.slug !== route.params.slug) return

    book.value = payload.data
    bookmarked.value = Boolean(payload.data.is_bookmarked)
    applyBookSeo(payload.data)
  } catch {
    // The review already succeeded; stale aggregates will refresh on next load.
  }
}

async function submitReview() {
  if (!book.value || reviewSubmitting.value) return

  reviewError.value = ''
  reviewSuccess.value = ''

  if (!auth.user) {
    await router.push({ name: 'masuk', query: { redirect: route.fullPath } })
    return
  }

  const body = reviewText.value.trim()

  if (body.length < 10) {
    reviewError.value = 'Tulis ulasan minimal 10 karakter agar bermanfaat bagi pembaca lain.'
    return
  }

  reviewSubmitting.value = true

  try {
    const payload = await apiFetch('/api/buku/' + book.value.slug + '/ulasan', {
      method: 'POST',
      body: JSON.stringify({ rating: reviewRating.value, body }),
    })

    const review = payload.data
    const index = book.value.reviews.findIndex((item) => item.id === review.id)

    if (index === -1) {
      book.value.reviews.unshift(review)
    } else {
      book.value.reviews[index] = review
    }

    book.value.rating = payload.meta.rating
    book.value.reviews_count = payload.meta.reviews_count

    reviewSuccess.value = payload.message || 'Ulasanmu sudah terbit.'
    reviewFormOpen.value = false
    reviewText.value = ''
    reviewRating.value = 5

    await syncBook()
  } catch (caught) {
    reviewError.value = errorMessage(caught)
  } finally {
    reviewSubmitting.value = false
  }
}

async function toggleHelpful(review) {
  if (!book.value || helpfulBusy.value[review.id]) return

  if (!auth.user) {
    await router.push({ name: 'masuk', query: { redirect: route.fullPath } })
    return
  }

  reviewError.value = ''
  helpfulBusy.value[review.id] = true

  try {
    const payload = await apiFetch('/api/ulasan/' + review.id + '/bermanfaat', { method: 'POST' })

    review.helpful = payload.data.helpful
    review.helpfuls = payload.data.helpfuls
  } catch (caught) {
    reviewError.value = errorMessage(caught)
  } finally {
    helpfulBusy.value[review.id] = false
  }
}

// --- Lifecycle -----------------------------------------------------------
watch(() => route.params.slug, fetchBook)

onMounted(fetchBook)
</script>
